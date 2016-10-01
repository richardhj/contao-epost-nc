<?php
/**
 * Clockwork SMS gateway for the notification_center extension for Contao Open Source CMS
 *
 * Copyright (c) 2016 Richard Henkenjohann
 *
 * @package NotificationCenterClockworkSMS
 * @author  Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */


/** @noinspection PhpUndefinedMethodInspection */
$table = NotificationCenter\Model\Gateway::getTable();


/**
 * Palettes
 */
$GLOBALS['TL_DCA'][$table]['palettes']['epost'] = '{title_legend},title,type;{gateway_legend},epost_user,epost_fallback_gateway';


/**
 * Fields
 */
$GLOBALS['TL_DCA'][$table]['fields']['epost_user'] = [
    'label'         => &$GLOBALS['TL_LANG'][$table]['epost_user'],
    'exclude'       => true,
    'inputType'     => 'select',
    'foreignKey'    => EPost\Model\User::getTable().'.title',
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
            /** @var EPost\Model\User $user */
            $user = EPost\Model\User::findByPk($value);

            if (null === $user || $user::OAUTH2_RESOURCE_OWNER_PASSWORD_CREDENTIALS_GRANT !== $user->authorization) {
                throw new Exception('Passwort muss hinterlegt sein');
            }

            return $value;
        },
    ],
    'sql'           => "int(10) unsigned NOT NULL default '0'",
];

/** @noinspection PhpUndefinedMethodInspection */
$GLOBALS['TL_DCA'][$table]['fields']['epost_fallback_gateway'] = [
    'label'            => &$GLOBALS['TL_LANG'][$table]['epost_fallback_gateway'],
    'exclude'          => true,
    'inputType'        => 'select',
    'options_callback' => function () {
        /** @noinspection PhpUndefinedMethodInspection */
        /** @var NotificationCenter\Model\Gateway|\Model\Collection $gateways */
        $gateways = NotificationCenter\Model\Gateway::findBy('type', 'queue');

        return (null === $gateways) ? [] : $gateways->fetchEach('title');
    },
    'relation'         => [
        'type'  => 'hasOne',
        'table' => NotificationCenter\Model\Gateway::getTable(),
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
                /** @noinspection PhpUndefinedMethodInspection */
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
