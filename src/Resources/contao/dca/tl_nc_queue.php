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
 * Fields
 */
$GLOBALS['TL_DCA']['tl_nc_queue']['fields']['epost_error_description'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_nc_queue']['epost_error_description'],
    'sql'   => 'text NULL',
];
