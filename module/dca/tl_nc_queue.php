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
$table = NotificationCenter\Model\QueuedMessage::getTable();


/**
 * Fields
 */
$GLOBALS['TL_DCA'][$table]['fields']['epost_error_description'] = [
    'label' => &$GLOBALS['TL_LANG'][$table]['epost_error_description'],
    'sql'   => "text NULL",
];
