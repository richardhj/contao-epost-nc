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

use Richardhj\ContaoEPostCoreBundle\Model\User;

/**
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_nc_gateway']['palettes']['epost'] =
    '{title_legend},title,type;{gateway_legend},epost_user,epost_fallback_gateway';


/**
 * Fields
 */
$GLOBALS['TL_DCA']['tl_nc_gateway']['fields']['epost_user'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_nc_gateway']['epost_user'],
    'exclude'       => true,
    'inputType'     => 'select',
    'foreignKey'    => 'tl_epost_user.title',
    'relation'      => [
        'type' => 'hasOne',
    ],
    'eval'          => [
        'mandatory' => true,
        'chosen'    => true,
        'tl_class'  => 'w50',
    ],
    'save_callback' => [
        function ($value) {
            /** @var User $user */
            $user = User::findByPk($value);

            if (null === $user || $user::OAUTH2_RESOURCE_OWNER_PASSWORD_CREDENTIALS_GRANT !== $user->authorization) {
                throw new Exception('Passwort muss hinterlegt sein');
            }

            return $value;
        },
    ],
    'sql'           => "int(10) unsigned NOT NULL default '0'",
];

$GLOBALS['TL_DCA']['tl_nc_gateway']['fields']['epost_fallback_gateway'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_nc_gateway']['epost_fallback_gateway'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => function () {
        /** @var NotificationCenter\Model\Gateway|\Model\Collection $gateways */
        $gateways = NotificationCenter\Model\Gateway::findBy('type', 'queue');

        return (null === $gateways) ? [] : $gateways->fetchEach('title');
    },
    'relation'         => [
        'type'  => 'hasOne',
        'table' => 'tl_nc_gateway',
    ],
    'eval'             => [
        'chosen'             => true,
        'tl_class'           => 'w50',
        'includeBlankOption' => true,
    ],
    'save_callback'    => [
        function ($id, \DataContainer $dc) {
            if ($id) {
                /** @var NotificationCenter\Model\Gateway|\Model $gateway */
                $gateway = NotificationCenter\Model\Gateway::findByPk($id);

                if (null === $gateway || $dc->id !== $gateway->queue_targetGateway) {
                    throw new Exception('Die ausgewählte Queue muss dieses Gateway verwenden.');
                }
            }

            return $id;
        },
    ],
    'sql'              => "int(10) unsigned NOT NULL default '0'",
];
