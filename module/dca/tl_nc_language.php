<?php
/**
 * Clockwork SMS gateway for the notification_center extension for Contao Open Source CMS
 *
 * Copyright (c) 2016 Richard Henkenjohann
 *
 * @package NotificationCenterClockworkSMS
 * @author  Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */
use EPost\Api\Metadata\DeliveryOptions;
use EPost\Api\Metadata\Envelope;
use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;


/** @noinspection PhpUndefinedMethodInspection */
$table = Language::getTable();


/**
 * MetaPalettes
 */
$GLOBALS['TL_DCA'][$table]['metapalettes']['epost'] = [
    'general'     => [
        'language',
        'fallback',
    ],
    'meta'        => [
        'epost_recipient_fields',
        'epost_explanation',
    ],
    'content'     => [
        'epost_letter_type',
    ],
    'attachments' => [
        'attachments',
        'attachment_tokens',
    ],
];


/**
 * MetaSubSelectPalettes
 */
$GLOBALS['TL_DCA'][$table]['metasubselectpalettes'] = [
    'epost_letter_type'       => [
        Envelope::LETTER_TYPE_NORMAL => [],
        Envelope::LETTER_TYPE_HYBRID => [
            'epost_cover_letter_mode',
        ],
    ],
    'epost_cover_letter_mode' =>
        [
            DeliveryOptions::OPTION_COVER_LETTER_INCLUDED => [],
            DeliveryOptions::OPTION_COVER_LETTER_GENERATE => [
                'epost_subject',
                'epost_cover_letter',
            ],
        ],
];


/**
 * Fields
 */
$GLOBALS['TL_DCA'][$table]['fields']['epost_explanation'] = [
    'label'   => &$GLOBALS['TL_LANG'][$table]['epost_explanation'],
    'exclude' => true,
//    'inputType' => 'justexplanation',
    'eval'    => [
        'content' => <<<'HTML'
<ul>
    <li>Es muss mindestens ein Anhang verwendet werden:
        <ul>
            <li>Wenn Sie das Anschreiben automatisch generieren lassen, können Sie einen einfachen statischen Anhang aus
                dem Dateisystem und/oder dynamische Anhänge per Tokens verwenden.
            </li>
            <li>Wenn Sie das Anschreiben nicht automatisch generieren lassen, müssen Sie mindestens einen dynamischen
                Anhang auswählen, welcher dann auch den formartierten Adressblock des Empfängers enthält (dies kann kein
                statischer Anhang).
            </li>
            <li>
                Dynamische Anhänge werden vor statischen Anhängen gedruckt.
            </li>
        </ul>
    </li>
</ul>
HTML
        ,
    ],
];

$GLOBALS['TL_DCA'][$table]['fields']['epost_recipient_fields'] = [
    'label'     => &$GLOBALS['TL_LANG'][$table]['epost_recipient_fields'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => [
        'columnFields' => [
            'recipient_field' => [
                'label'            => &$GLOBALS['TL_LANG'][$table]['epost_recipient_field'],
                'exclude'          => true,
                'inputType'        => 'select',
                'options_callback' => function () {
                    return [
                        Envelope::LETTER_TYPE_NORMAL => Envelope\Recipient\Normal::getConfigurableFields(),
                        Envelope::LETTER_TYPE_HYBRID => Envelope\Recipient\Hybrid::getConfigurableFields(),
                    ];
                },
                'reference'        => array_merge(
                    (array)$GLOBALS['TL_LANG']['MSC']['epost']['letterTypes'],
                    (array)$GLOBALS['TL_LANG']['MSC']['epost']['recipientFields']
                ),
                'eval'             => [
                    'chosen'             => true,
                    'includeBlankOption' => true,
                    'style'              => 'width: 210px',
                ],
            ],
            'recipient_value' => [
                'label'     => &$GLOBALS['TL_LANG'][$table]['epost_recipient_value'],
                'exclude'   => true,
                'inputType' => 'text',
                'eval'      => [
//                    'rgxp'           => 'nc_tokens',
                    'decodeEntities' => true,
                ],
            ],
        ],
        'rgxp'         => 'nc_tokens',
        'tl_class'     => 'clr',
    ],
    'sql'       => "text NULL",
];

$GLOBALS['TL_DCA'][$table]['fields']['epost_letter_type'] = [
    'label'            => &$GLOBALS['TL_LANG'][$table]['epost_letter_type'],
    'exclude'          => true,
    'inputType'        => 'justtextoption',
    'options_callback' => function () {
        return Envelope::getLetterTypeOptions();
    },
    'reference'        => &$GLOBALS['TL_LANG']['MSC']['epost']['letterTypes'],
    'eval'             => [
        'tl_class' => 'w50',
    ],
    'load_callback'    => [
        function ($value, \DataContainer $dc) {
            /** @noinspection PhpUndefinedMethodInspection */
            $message = Message::findBy(
                [sprintf('id=(SELECT id FROM %s WHERE pid=?)', Language::getTable())],
                [$dc->id]
            );

            return $message->epost_letter_type;
        },
    ],
    'sql'              => "varchar(64) NOT NULL default ''", // DO NOT REMOVE field from database
];

$GLOBALS['TL_DCA'][$table]['fields']['epost_cover_letter_mode'] = [
    'label'            => &$GLOBALS['TL_LANG'][$table]['epost_cover_letter_mode'],
    'exclude'          => true,
    'inputType'        => 'radio',
    'options_callback' => function () {
        return DeliveryOptions::getOptionsForCoverLetter();
    },
    'reference'        => &$GLOBALS['TL_LANG'][$table]['epost_cover_letter_modes'],
    'eval'             => [
        'tl_class'       => 'w50',
        'submitOnChange' => true,
    ],
    'sql'              => "char(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['epost_subject'] = [
    'label'     => &$GLOBALS['TL_LANG'][$table]['epost_subject'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'rgxp'     => 'nc_tokens',
        'tl_class' => 'clr long',
    ],
    'sql'       => "text NULL",
];

$GLOBALS['TL_DCA'][$table]['fields']['epost_cover_letter'] = [
    'label'     => &$GLOBALS['TL_LANG'][$table]['epost_cover_letter'],
    'exclude'   => true,
    'inputType' => 'textarea',
    'eval'      => [
        'rgxp'           => 'nc_tokens',
        'tl_class'       => 'clr',
        'rte'            => 'tinyEPost',
        'decodeEntities' => true,
        'allowHtml'      => true,
        'mandatory'      => true,
    ],
    'sql'       => "text NULL",
];
