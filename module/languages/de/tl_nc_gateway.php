<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 *
 * Copyright (c) 2015-2016 Richard Henkenjohann
 *
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */


/** @noinspection PhpUndefinedMethodInspection */
$table = NotificationCenter\Model\Gateway::getTable();


/**
 * Types
 */
$GLOBALS['TL_LANG'][$table]['type']['epost'] = 'E-POSTBUSINESS';


/**
 * Fields
 */
$GLOBALS['TL_LANG'][$table]['epost_user'][0] = 'API-Benutzer';
$GLOBALS['TL_LANG'][$table]['epost_user'][1] = 'Wählen Sie den Account aus, über den die Briefe verschickt werden sollen. Damit der Benutzer ausgewählt werden kann, muss das Passwort im System hinterlegt sein.';
$GLOBALS['TL_LANG'][$table]['epost_fallback_gateway'][0] = 'Fallback-Gateway';
$GLOBALS['TL_LANG'][$table]['epost_fallback_gateway'][1] = 'Wählen Sie eine Queue aus, in die der Brief eingereiht wird, wenn er aufgrund eines Fehlers nicht versendet werden konnte. Verhindert, dass ein Brief nicht "verloren" geht.';
