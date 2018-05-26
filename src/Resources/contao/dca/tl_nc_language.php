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

use NotificationCenter\Model\Language;
use NotificationCenter\Model\Message;
use Richardhj\EPost\Api\Metadata\DeliveryOptions;
use Richardhj\EPost\Api\Metadata\Envelope;


/**
 * MetaPalettes
 */
$GLOBALS['TL_DCA']['tl_nc_language']['metapalettes']['epost'] = [
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
$GLOBALS['TL_DCA']['tl_nc_language']['metasubselectpalettes'] = [
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
$GLOBALS['TL_DCA']['tl_nc_language']['fields']['epost_explanation'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['epost_explanation'],
    'exclude'   => true,
    'inputType' => 'justexplanation',
    'eval'      => [
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

$GLOBALS['TL_DCA']['tl_nc_language']['fields']['epost_recipient_fields'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['epost_recipient_fields'],
    'exclude'   => true,
    'inputType' => 'multiColumnWizard',
    'eval'      => [
        'columnFields' => [
            'recipient_field' => [
                'label'            => &$GLOBALS['TL_LANG']['tl_nc_language']['epost_recipient_field'],
                'exclude'          => true,
                'inputType'        => 'select',
                'options_callback' => function () {
                    return [
                        Envelope::LETTER_TYPE_NORMAL => [
                            'displayName',
                            'epostAddress',
                        ],
                        Envelope::LETTER_TYPE_HYBRID => [
                            'company',
                            'salutation',
                            'title',
                            'firstName',
                            'lastName',
                            'streetName',
                            'houseNumber',
                            'addressAddOn',
                            'postOfficeBox',
                            'zipCode',
                            'city',
                        ],
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
                'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['epost_recipient_value'],
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
    'sql'       => 'text NULL',
];

$GLOBALS['TL_DCA']['tl_nc_language']['fields']['epost_letter_type'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_nc_language']['epost_letter_type'],
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
            $message = Message::findBy(
                ['id=(SELECT pid FROM tl_nc_language WHERE id=?)'],
                [$dc->id]
            );

            return $message->epost_letter_type;
        },
    ],
    'sql'              => "varchar(64) NOT NULL default ''", // DO NOT REMOVE field from database
];

$GLOBALS['TL_DCA']['tl_nc_language']['fields']['epost_cover_letter_mode'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_nc_language']['epost_cover_letter_mode'],
    'exclude'          => true,
    'inputType'        => 'radio',
    'options_callback' => function () {
        return DeliveryOptions::getOptionsForCoverLetter();
    },
    'reference'        => &$GLOBALS['TL_LANG']['tl_nc_language']['epost_cover_letter_modes'],
    'eval'             => [
        'tl_class'       => 'w50',
        'submitOnChange' => true,
    ],
    'sql'              => "char(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_nc_language']['fields']['epost_subject'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['epost_subject'],
    'exclude'   => true,
    'inputType' => 'text',
    'eval'      => [
        'rgxp'     => 'nc_tokens',
        'tl_class' => 'clr long',
    ],
    'sql'       => 'text NULL',
];

$GLOBALS['TL_DCA']['tl_nc_language']['fields']['epost_cover_letter'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_nc_language']['epost_cover_letter'],
    'exclude'   => true,
    'inputType' => 'textarea',
    'eval'      => [
        'rgxp'           => 'nc_tokens',
        'tl_class'       => 'clr',
        'rte'            => 'tinyMCE',
        'decodeEntities' => true,
        'allowHtml'      => true,
        'mandatory'      => true,
    ],
    'sql'       => 'text NULL',
];
