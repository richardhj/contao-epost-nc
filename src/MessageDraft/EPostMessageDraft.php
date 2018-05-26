<?php

/**
 * This file is part of richardhj/contao-epost-nc.
 *
 * Copyright (c) 2015-2018 Richard Henkenjohann
 *
 * @package   richardhj/contao-epost-nc
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright 2015-2018 Richard Henkenjohann
 * @license   https://github.com/richardhj/contao-epost-nc/blob/master/LICENSE
 */

namespace Richardhj\ContaoEPostNotificationCenterBundle\MessageDraft;

use Contao\FilesModel;
use NotificationCenter\MessageDraft\MessageDraftInterface;
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;
use NotificationCenter\Util\StringUtil;
use Richardhj\EPost\Api\Metadata\DeliveryOptions;
use Richardhj\EPost\Api\Metadata\Envelope;
use Richardhj\EPost\Api\Metadata\Envelope\AbstractRecipient;


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
    private $message;

    /**
     * Language
     * @var Language|\Model
     */
    private $language;

    /**
     * Tokens
     * @var array
     */
    private $tokens = [];

    /**
     * Construct the object
     *
     * @param Message  $message
     * @param Language $language
     * @param          $tokens
     */
    public function __construct(Message $message, Language $language, $tokens)
    {
        $this->tokens   = $tokens;
        $this->language = $language;
        $this->message  = $message;
    }

    /**
     * Get the cover letter mode (whether the cover letter is included or should be generated)
     *
     * @return string
     */
    public function getCoverLetterMode(): string
    {
        return $this->language->epost_cover_letter_mode;
    }

    /**
     * Get the recipient
     *
     * @return AbstractRecipient
     */
    public function getRecipient()
    {
        $fields = deserialize($this->language->epost_recipient_fields);

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
        foreach ((array) $fields as $field) {
            try {
                $recipient->{$field['recipient_field']} = StringUtil::recursiveReplaceTokensAndTags(
                    $field['recipient_value'],
                    $this->tokens,
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
    public function getSubject(): string
    {
        return (DeliveryOptions::OPTION_COVER_LETTER_GENERATE === $this->getCoverLetterMode())
            ? $this->language->epost_subject
            : '';
    }

    /**
     * Get the cover letter (html formatted)
     *
     * @return string
     */
    public function getCoverLetter(): string
    {
        if (DeliveryOptions::OPTION_COVER_LETTER_INCLUDED) {
            return '';
        }

        $buffer = StringUtil::recursiveReplaceTokensAndTags($this->language->epost_cover_letter, $this->tokens);

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
        $attachments = StringUtil::getTokenAttachments($this->language->attachment_tokens, $this->tokens);

        // Add static attachments
        $staticAttachments = deserialize($this->language->attachments, true);

        if (!empty($staticAttachments)) {
            $files = FilesModel::findMultipleByUuids($staticAttachments);

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
        return $this->language->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getTokens()
    {
        return $this->tokens;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getLanguage()
    {
        return $this->language->language;
    }
}
