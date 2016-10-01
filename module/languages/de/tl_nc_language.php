<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */
use EPost\Api\Metadata\DeliveryOptions;


/** @noinspection PhpUndefinedMethodInspection */
$table = NotificationCenter\Model\Language::getTable();


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['epost_explanation'][0] = 'Hinweise';
$GLOBALS['TL_LANG'][$table]['epost_recipient_fields'][0] = 'Empfänger-Felder';
$GLOBALS['TL_LANG'][$table]['epost_recipient_fields'][1] = 'Füllen Sie die vorgegebenen Felder mit den entsprechenden Tokens.';
$GLOBALS['TL_LANG'][$table]['epost_recipient_field'][0] = 'vorgegebenes Feld';
$GLOBALS['TL_LANG'][$table]['epost_recipient_value'][0] = 'zugewiesener Wert';
$GLOBALS['TL_LANG'][$table]['epost_letter_type'][0] = 'Art des Briefes';
$GLOBALS['TL_LANG'][$table]['epost_letter_type'][1] = 'Die Art des Briefes wurde bei den Einstellungen von der Nachricht angegeben.';
$GLOBALS['TL_LANG'][$table]['epost_cover_letter_mode'][0] = 'Das Anschreiben…';
$GLOBALS['TL_LANG'][$table]['epost_cover_letter_mode'][1] = '';
$GLOBALS['TL_LANG'][$table]['epost_subject'][0] = 'Betreff';
$GLOBALS['TL_LANG'][$table]['epost_subject'][1] = 'Geben Sie optional den Betreff des Anschreibens an.';
$GLOBALS['TL_LANG'][$table]['epost_cover_letter'][0] = 'Anschreiben';
$GLOBALS['TL_LANG'][$table]['epost_cover_letter'][1] = 'Geben Sie das Anschreiben ein.';


/**
 * References
 */
$GLOBALS['TL_LANG'][$table]['epost_cover_letter_modes'] = [
    DeliveryOptions::OPTION_COVER_LETTER_GENERATE => '…soll automatisch generiert werden.',
    DeliveryOptions::OPTION_COVER_LETTER_INCLUDED => '…ist beim dynamischen Anhang enthalten.',
];
