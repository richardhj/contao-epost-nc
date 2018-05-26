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

/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_nc_language']['epost_explanation'][0]       = 'Hinweise';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_recipient_fields'][0]  = 'Empfänger-Felder';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_recipient_fields'][1]  = 'Füllen Sie die vorgegebenen Felder mit den entsprechenden Tokens.';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_recipient_field'][0]   = 'vorgegebenes Feld';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_recipient_value'][0]   = 'zugewiesener Wert';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_letter_type'][0]       = 'Art des Briefes';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_letter_type'][1]       = 'Die Art des Briefes wurde bei den Einstellungen von der Nachricht angegeben.';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_cover_letter_mode'][0] = 'Das Anschreiben…';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_cover_letter_mode'][1] = '';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_subject'][0]           = 'Betreff';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_subject'][1]           = 'Geben Sie optional den Betreff des Anschreibens an.';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_cover_letter'][0]      = 'Anschreiben';
$GLOBALS['TL_LANG']['tl_nc_language']['epost_cover_letter'][1]      = 'Geben Sie das Anschreiben ein.';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_nc_language']['epost_cover_letter_modes'] = [
    DeliveryOptions::OPTION_COVER_LETTER_GENERATE => '…soll automatisch generiert werden.',
    DeliveryOptions::OPTION_COVER_LETTER_INCLUDED => '…ist beim dynamischen Anhang enthalten.',
];
