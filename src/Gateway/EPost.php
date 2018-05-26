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

namespace Richardhj\ContaoEPostNotificationCenterBundle\Gateway;

use Contao\System;
use GuzzleHttp\Exception\ClientException;
use NotificationCenter\Gateway\Base;
use NotificationCenter\Gateway\GatewayInterface;
use NotificationCenter\MessageDraft\MessageDraftFactoryInterface;
use NotificationCenter\MessageDraft\MessageDraftInterface;
use NotificationCenter\Model\Gateway;
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;
use NotificationCenter\Model\QueuedMessage;
use Richardhj\ContaoEPostCoreBundle\Model\User;
use Richardhj\ContaoEPostNotificationCenterBundle\MessageDraft\EPostMessageDraft;
use Richardhj\EPost\Api\Letter;
use Richardhj\EPost\Api\Metadata\DeliveryOptions;
use Richardhj\EPost\Api\Metadata\Envelope;


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
     * @param   Message|\Model $message
     * @param   array          $tokens
     * @param   string         $language
     *
     * @return  MessageDraftInterface|null (if no draft could be found)
     */
    public function createDraft(Message $message, array $tokens, $language = ''): ?MessageDraftInterface
    {
        if ('' === $language) {
            $language = $GLOBALS['TL_LANGUAGE'];
        }

        if (null === ($languageModel = Language::findByMessageAndLanguageOrFallback($message, $language))) {
            System::log(
                sprintf(
                    'Could not find matching language or fallback for message ID "%s" and language "%s".',
                    $message->id,
                    $language
                ),
                __METHOD__,
                TL_ERROR
            );

            return null;
        }

        return new EPostMessageDraft($message, $languageModel, $tokens);
    }


    /**
     * Send Clockwork request message
     *
     * @param   Message|\Model $objMessage
     * @param   array          $arrTokens
     * @param   string         $strLanguage
     *
     * @return  bool
     * @throws \Exception
     */
    public function send(Message $objMessage, array $arrTokens, $strLanguage = ''): bool
    {
//		if ($this->objModel->clockwork_api_key == '')
//		{
//			\System::log(sprintf('Please provide the Clockwork API key for message ID "%s"', $objMessage->id), __METHOD__, TL_ERROR);
//
//			return false;
//		}

        /** @var User $user */
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
