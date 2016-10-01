<?php
/**
 * Clockwork SMS gateway for the notification_center extension for Contao Open Source CMS
 *
 * Copyright (c) 2016 Richard Henkenjohann
 *
 * @package NotificationCenterClockworkSMS
 * @author  Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace NotificationCenter\MessageDraft;

use EPost\Api\Metadata\DeliveryOptions;
use EPost\Api\Metadata\Envelope;
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;
use NotificationCenter\Util\StringUtil;


/**
 * Class EPostMessageDraft
 * @package NotificationCenter\MessageDraft
 */
class EPostMessageDraft implements MessageDraftInterface
{

    /**
     * Message
     * @var Message|\Model
     */
    protected $objMessage;


    /**
     * Language
     * @var Language|\Model
     */
    protected $objLanguage;


    /**
     * Tokens
     * @var array
     */
    protected $arrTokens = [];


    /**
     * Construct the object
     *
     * @param Message  $objMessage
     * @param Language $objLanguage
     * @param          $arrTokens
     */
    public function __construct(Message $objMessage, Language $objLanguage, $arrTokens)
    {
        $this->arrTokens = $arrTokens;
        $this->objLanguage = $objLanguage;
        $this->objMessage = $objMessage;
    }


    /**
     * Get the cover letter mode (whether the cover letter is included or should be generated)
     *
     * @return string
     */
    public function getCoverLetterMode()
    {
        return $this->objLanguage->epost_cover_letter_mode;
    }


    /**
     * Get the recipient
     *
     * @return Envelope\AbstractRecipient
     */
    public function getRecipient()
    {
        $fields = deserialize($this->objLanguage->epost_recipient_fields);

        switch ($this->getMessage()->epost_letter_type) {
            case Envelope::LETTER_TYPE_NORMAL:
                $recipient = new Envelope\Recipient\Normal();
                break;

            case Envelope::LETTER_TYPE_HYBRID:
                $recipient = new Envelope\Recipient\Hybrid();
                break;

            default:
                return null;
        }

        // Set each property
        foreach ($fields as $field) {
            try {
                $recipient->{$field['recipient_field']} = StringUtil::recursiveReplaceTokensAndTags(
                    $field['recipient_value'],
                    $this->arrTokens,
                    StringUtil::NO_TAGS | StringUtil::NO_BREAKS
                );
            } catch (\InvalidArgumentException $e) {
            }
        }

        return $recipient;
    }


    /**
     * Get the subject
     *
     * @return string
     */
    public function getSubject()
    {
        return (DeliveryOptions::OPTION_COVER_LETTER_GENERATE === $this->getCoverLetterMode())
            ? $this->objLanguage->epost_subject
            : '';
    }


    /**
     * Get the cover letter (html formatted)
     *
     * @return string
     */
    public function getCoverLetter()
    {
        if (DeliveryOptions::OPTION_COVER_LETTER_INCLUDED) {
            return '';
        }

        $buffer = StringUtil::recursiveReplaceTokensAndTags($this->objLanguage->epost_cover_letter, $this->arrTokens);

        $search = array
        (
            '@<span style="text-decoration: ?underline;?">(.*)</span>@Us',
            '@<span style="text-decoration: ?line-through;?">(.*)</span>@Us',
            '@<p style="text-align: ?center;?">(.*)</p>@Us',
        );
        $replace = array
        (
            '<u>$1</u>',
            '<strike>$1</strike>',
            '<center>$1</center>',
        );

        $buffer = preg_replace($search, $replace, $buffer);

        return $buffer;
    }


    /**
     * Returns the paths to attachments as an array
     *
     * @return  array
     */
    public function getAttachments()
    {
        // Token attachments
        $attachments = StringUtil::getTokenAttachments($this->objLanguage->attachment_tokens, $this->arrTokens);

        // Add static attachments
        $staticAttachments = deserialize($this->objLanguage->attachments, true);

        if (!empty($staticAttachments)) {
            $files = \FilesModel::findMultipleByUuids($staticAttachments);

            if (null === $files) {
                return $attachments;
            }

            while ($files->next()) {
                $attachments[] = TL_ROOT.'/'.$files->path;
            }
        }

        return $attachments;
    }


    public function getLanguageId()
    {
        return $this->objLanguage->id;
    }


    /**
     * {@inheritdoc}
     */
    public function getTokens()
    {
        return $this->arrTokens;
    }


    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->objMessage;
    }


    /**
     * {@inheritdoc}
     */
    public function getLanguage()
    {
        return $this->objLanguage->language;
    }
}
