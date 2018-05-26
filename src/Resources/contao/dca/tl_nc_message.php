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

use Richardhj\EPost\Api\Metadata\DeliveryOptions;
use Richardhj\EPost\Api\Metadata\Envelope;


/**
 * MetaPalettes
 */
$GLOBALS['TL_DCA']['tl_nc_message']['metapalettes']['epost'] = [
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
$GLOBALS['TL_DCA']['tl_nc_message']['metasubselectpalettes'] = [
    'epost_letter_type' => [
        Envelope::LETTER_TYPE_NORMAL => [],
        Envelope::LETTER_TYPE_HYBRID => [
            'epost_color',
            'epost_registered',
        ],
    ],
];


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_nc_message']['fields']['epost_letter_type'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_nc_message']['epost_letter_type'],
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

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['epost_registered'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_nc_message']['epost_registered'],
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

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['epost_color'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_nc_message']['epost_color'],
    'exclude'          => true,
    'inputType'        => 'radio',
    'options_callback' => function () {
        return DeliveryOptions::getOptionsForColor();
    },
    'reference'        => &$GLOBALS['TL_LANG']['tl_nc_message']['epost_colors'],
    'default'          => DeliveryOptions::OPTION_COLOR_GRAYSCALE,
    'eval'             => [
        'mandatory' => true,
        'tl_class'  => 'w50 clr',
    ],
    'sql'              => "varchar(64) NOT NULL default ''",
];

$GLOBALS['TL_DCA']['tl_nc_message']['fields']['epost_price_information'] = [
    'label'   => &$GLOBALS['TL_LANG']['tl_nc_message']['epost_price_information'],
    'exclude' => true,
    //    'inputType' => 'justexplanation',
    //	'options_callback' => function ()
    //	{
    //		return DeliveryOptions::getOptionsForColor();
    //	},
    //	'default' => DeliveryOptions::OPTION_COLOR_GRAYSCALE,
    'eval'    => [
        'tl_class' => 'clr',
        'content'  => 'test',
    ],
    //	'sql'              => "varchar(64) NOT NULL default ''"
];