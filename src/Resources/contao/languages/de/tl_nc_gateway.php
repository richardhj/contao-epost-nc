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

/**
 * Types
 */
$GLOBALS['TL_LANG']['tl_nc_gateway']['type']['epost'] = 'E-POSTBUSINESS';


/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_nc_gateway']['epost_user'][0]             = 'API-Benutzer';
$GLOBALS['TL_LANG']['tl_nc_gateway']['epost_user'][1]             = 'Wählen Sie den Account aus, über den die Briefe verschickt werden sollen. Damit der Benutzer ausgewählt werden kann, muss das Passwort im System hinterlegt sein.';
$GLOBALS['TL_LANG']['tl_nc_gateway']['epost_fallback_gateway'][0] = 'Fallback-Gateway';
$GLOBALS['TL_LANG']['tl_nc_gateway']['epost_fallback_gateway'][1] = 'Wählen Sie eine Queue aus, in die der Brief eingereiht wird, wenn er aufgrund eines Fehlers nicht versendet werden konnte. Verhindert, dass ein Brief nicht "verloren" geht.';
