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
$table = NotificationCenter\Model\Message::getTable();


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['epost_letter_type'][0] = 'Art des Briefes';
$GLOBALS['TL_LANG'][$table]['epost_letter_type'][1] = 'Wählen Sie aus, ob der E‑POSTBRIEF elektronisch oder physisch verschickt werden soll.';
$GLOBALS['TL_LANG'][$table]['epost_registered'][0] = 'Briefzusatzleistungen';
$GLOBALS['TL_LANG'][$table]['epost_registered'][1] = 'Geben Sie an, ob der E‑POSTBRIEF als Einschreiben versendet soll und wenn ja, welcher Einschreiben-Typ gewählt werden soll.';
$GLOBALS['TL_LANG'][$table]['epost_color'][0] = 'Farbdruck';
$GLOBALS['TL_LANG'][$table]['epost_color'][1] = 'Wählen Sie, ob der Brief in schwarz-weiß oder in Farbe gedruckt werden soll.';
$GLOBALS['TL_LANG'][$table]['epost_price_information'][0] = 'Informationen zu den Preisen';


/**
 * References
 */
$GLOBALS['TL_LANG'][$table]['epost_colors'] = [
    DeliveryOptions::OPTION_COLOR_GRAYSCALE => 'nicht aktivieren',
    DeliveryOptions::OPTION_COLOR_COLORED   => 'aktivieren',
];
