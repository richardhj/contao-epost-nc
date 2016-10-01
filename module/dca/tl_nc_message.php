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
use NotificationCenter\Util\EPostHelper;


/** @noinspection PhpUndefinedMethodInspection */
$table = NotificationCenter\Model\Message::getTable();


/**
 * MetaPalettes
 */
$GLOBALS['TL_DCA'][$table]['metapalettes']['epost'] = [
    'title'     => [
        'title',
        'gateway',
    ],
    'languages' => [
        'languages',
    ],
    'expert'    => [
        'epost_letter_type',
        'epost_price_information',
    ],
    'publish'   => [
        'published',
    ],
];


/**
 * MetaSubSelectPalettes
 */
$GLOBALS['TL_DCA'][$table]['metasubselectpalettes'] = [
    'epost_letter_type'       => [
        Envelope::LETTER_TYPE_NORMAL => [],
        Envelope::LETTER_TYPE_HYBRID => [
            'epost_color',
            'epost_registered',
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
$GLOBALS['TL_DCA'][$table]['fields']['epost_letter_type'] = [
    'label'            => &$GLOBALS['TL_LANG'][$table]['epost_letter_type'],
    'exclude'          => true,
    'inputType'        => 'radio',
    'options_callback' => function () {
        return Envelope::getLetterTypeOptions();
    },
    'reference'        => &$GLOBALS['TL_LANG']['MSC']['epost']['letterTypes'],
    'eval'             => [
        'mandatory'      => true,
        'submitOnChange' => true,
        'tl_class'       => 'w50',
    ],
    'sql'              => "varchar(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['epost_registered'] = [
    'label'            => &$GLOBALS['TL_LANG'][$table]['epost_registered'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => function () {
        return DeliveryOptions::getOptionsForRegistered();
    },
    'reference'        => &$GLOBALS['TL_LANG']['MSC']['epost']['registeredOptions'],
    'default'          => DeliveryOptions::OPTION_REGISTERED_NO,
    'eval'             => [
        'mandatory' => true,
        'tl_class'  => 'w50',
    ],
    'sql'              => "varchar(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['epost_color'] = [
    'label'            => &$GLOBALS['TL_LANG'][$table]['epost_color'],
    'exclude'          => true,
    'inputType'        => 'radio',
    'options_callback' => function () {
        return DeliveryOptions::getOptionsForColor();
    },
    'reference'        => &$GLOBALS['TL_LANG'][$table]['epost_colors'],
    'default'          => DeliveryOptions::OPTION_COLOR_GRAYSCALE,
    'eval'             => [
        'mandatory' => true,
        'tl_class'  => 'w50 clr',
    ],
    'sql'              => "varchar(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA'][$table]['fields']['epost_price_information'] = [
    'label'   => &$GLOBALS['TL_LANG'][$table]['epost_price_information'],
    'exclude' => true,
//    'inputType' => 'justexplanation',
//	'options_callback' => function ()
//	{
//		return DeliveryOptions::getOptionsForColor();
//	},
//	'default' => DeliveryOptions::OPTION_COLOR_GRAYSCALE,
    'eval'    => [
        'tl_class' => 'clr',
        'content'  => EPostHelper::queryPriceInformationForDca(),
    ],
//	'sql'              => "varchar(64) NOT NULL default ''"
];