<?php
/**
 * Clockwork SMS gateway for the notification_center extension for Contao Open Source CMS
 *
 * Copyright (c) 2016 Richard Henkenjohann
 *
 * @package NotificationCenterClockworkSMS
 * @author  Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace NotificationCenter\Gateway;

use EPost\Api\Letter;
use EPost\Api\Metadata\DeliveryOptions;
use EPost\Api\Metadata\Envelope;
use EPost\Model\User;
use GuzzleHttp\Exception\ClientException;
use NotificationCenter\MessageDraft\EPostMessageDraft;
use NotificationCenter\MessageDraft\MessageDraftFactoryInterface;
use NotificationCenter\MessageDraft\MessageDraftInterface;
use NotificationCenter\Model\Gateway;
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;
use NotificationCenter\Model\QueuedMessage;


/**
 * Class EPost
 * @package NotificationCenter\Gateway
 */
class EPost extends Base implements GatewayInterface, MessageDraftFactoryInterface
{

    /**
     * The gateway model
     * @var Gateway|\Model
     */
    protected $objModel;


    /**
     * Returns a MessageDraft
     *
     * @param   Message|\Model $objMessage
     * @param   array          $arrTokens
     * @param   string         $strLanguage
     *
     * @return  MessageDraftInterface|null (if no draft could be found)
     */
    public function createDraft(Message $objMessage, array $arrTokens, $strLanguage = '')
    {
        if ('' === $strLanguage) {
            $strLanguage = $GLOBALS['TL_LANGUAGE'];
        }

        if (null === ($objLanguage = Language::findByMessageAndLanguageOrFallback($objMessage, $strLanguage))) {
            \System::log(
                sprintf(
                    'Could not find matching language or fallback for message ID "%s" and language "%s".',
                    $objMessage->id,
                    $strLanguage
                ),
                __METHOD__,
                TL_ERROR
            );

            return null;
        }

        return new EPostMessageDraft($objMessage, $objLanguage, $arrTokens);
    }


    /**
     * Send Clockwork request message
     *
     * @param   Message|\Model $objMessage
     * @param   array          $arrTokens
     * @param   string         $strLanguage
     *
     * @return  bool
     */
    public function send(Message $objMessage, array $arrTokens, $strLanguage = '')
    {
//		if ($this->objModel->clockwork_api_key == '')
//		{
//			\System::log(sprintf('Please provide the Clockwork API key for message ID "%s"', $objMessage->id), __METHOD__, TL_ERROR);
//
//			return false;
//		}

        /** @var User $user */
        /** @noinspection PhpUndefinedMethodInspection */
        $user = $this->getModel()->getRelated('epost_user');

        // Authenticate
        $token = $user->authenticate();

        // Create draft
        /** @var EPostMessageDraft $draft */
        $draft = $this->createDraft($objMessage, $arrTokens, $strLanguage);

        if (null === $draft) {
            return false;
        }

        // Create letter and envelope
        $letter = new Letter();
        $envelope = new Envelope();
        $envelope
            ->setSystemMessageType($draft->getMessage()->epost_letter_type)
            ->setSubject($draft->getSubject());

        // Set recipients and delivery options
        switch ($draft->getMessage()->epost_letter_type) {
            // Hybrid letter
            case Envelope::LETTER_TYPE_HYBRID:
                // Add recipients
                $envelope->addRecipientPrinted($draft->getRecipient());

                // Set delivery options
                $objDeliveryOptions = new DeliveryOptions();
                $objDeliveryOptions
                    ->setRegistered($draft->getMessage()->epost_registered)
                    ->setColor($draft->getMessage()->epost_color)
                    ->setCoverLetter($draft->getCoverLetterMode());

                $letter->setDeliveryOptions($objDeliveryOptions);

                break;

            // Normal letter
            case Envelope::LETTER_TYPE_NORMAL:
                // Add recipients
                $envelope->addRecipientNormal($draft->getRecipient());

                break;

            default:
                return false;
        }

        // Prepare letter
        $letter
            ->setTestEnvironment($user->test_environment)
            ->setAccessToken($token)
            ->setEnvelope($envelope)
            ->setCoverLetter($draft->getCoverLetter());

        // Set attachments
        foreach ($draft->getAttachments() as $attachment) {
            $letter->addAttachment($attachment);
        }

        // Create and send letter
        try {
            $letter
                ->create()
                ->send();

        } catch (ClientException $e) {
            $errorInformation = \GuzzleHttp\json_decode($e->getResponse()->getBody());

            // Import database just for getParentEntries()
            /** @noinspection PhpUndefinedMethodInspection */
            $this->import('Database');

            /** @noinspection PhpUndefinedMethodInspection */
            $errorDescription = sprintf(
                'The E-POST letter%s could not be sent due following error(s): <ol><li>%s</li></ol>',
                $this->getParentEntries(Language::getTable(), $draft->getLanguageId()),
                implode(
                    '</li> <li>',
                    array_map(
                        function ($error) {
                            return sprintf(
                                'Error (<em>%s</em>): <strong>%s</strong>',
                                $error->type,
                                $error->description
                            );
                        },
                        $errorInformation->error_details
                    )
                )
            );

            // Try to add to fallback gateway
            /** @var Gateway|\Model $fallbackGatewayModel */
            if (null !== ($fallbackGatewayModel = $this->objModel->getRelated('epost_fallback_gateway'))) {
                /** @var QueuedMessage|\Model $queuedMessage */
                $queuedMessage = new QueuedMessage();
                $queuedMessage->message = $objMessage->id;
                $queuedMessage->sourceQueue = $fallbackGatewayModel->id;
                $queuedMessage->targetGateway = $fallbackGatewayModel->queue_targetGateway;
                $queuedMessage->dateAdded = time();
                $queuedMessage->setTokens($arrTokens);
                $queuedMessage->language = $strLanguage;
                $queuedMessage->error = 1;
                $queuedMessage->epost_error_description = $errorDescription;

                $queuedMessage->save();
            }

            \System::log(strip_tags($errorDescription), __METHOD__, TL_ERROR);

            return false;
        }

        return true;
    }
}
