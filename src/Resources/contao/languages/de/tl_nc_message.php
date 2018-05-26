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
$GLOBALS['TL_LANG']['tl_nc_message']['epost_letter_type'][0]       = 'Art des Briefes';
$GLOBALS['TL_LANG']['tl_nc_message']['epost_letter_type'][1]       = 'Wählen Sie aus, ob der E‑POSTBRIEF elektronisch oder physisch verschickt werden soll.';
$GLOBALS['TL_LANG']['tl_nc_message']['epost_registered'][0]        = 'Briefzusatzleistungen';
$GLOBALS['TL_LANG']['tl_nc_message']['epost_registered'][1]        = 'Geben Sie an, ob der E‑POSTBRIEF als Einschreiben versendet soll und wenn ja, welcher Einschreiben-Typ gewählt werden soll.';
$GLOBALS['TL_LANG']['tl_nc_message']['epost_color'][0]             = 'Farbdruck';
$GLOBALS['TL_LANG']['tl_nc_message']['epost_color'][1]             = 'Wählen Sie, ob der Brief in schwarz-weiß oder in Farbe gedruckt werden soll.';
$GLOBALS['TL_LANG']['tl_nc_message']['epost_price_information'][0] = 'Informationen zu den Preisen';


/**
 * References
 */
$GLOBALS['TL_LANG']['tl_nc_message']['epost_colors'] = [
    DeliveryOptions::OPTION_COLOR_GRAYSCALE => 'nicht aktivieren',
    DeliveryOptions::OPTION_COLOR_COLORED   => 'aktivieren',
];
