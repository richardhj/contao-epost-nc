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

use Richardhj\ContaoEPostNotificationCenterBundle\Gateway\EPost;


/**
 * Notification Center Gateways
 */
$GLOBALS['NOTIFICATION_CENTER']['GATEWAY']['epost'] = EPost::class;


/**
 * Notification Center Notification Types
 */
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'] = array_merge_recursive(
    (array)$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'],
    [
        'contao'  => [
            'core_form'           => [
                'epost_recipient_fields' => ['form_*', 'formconfig_*', 'formlabel_*', 'raw_data'],
                'epost_subject'          => ['form_*', 'formconfig_*', 'formlabel_*', 'raw_data', 'admin_email'],
                'epost_cover_letter'     => ['form_*', 'formconfig_*', 'formlabel_*', 'raw_data', 'admin_email'],
            ],
            'member_registration' => [
                'epost_recipient_fields' => ['member_*'],
                'epost_subject'          => ['member_*', 'domain'],
                'epost_cover_letter'     => ['domain', 'link', 'member_*', 'admin_email'],

            ],
        ],
        'isotope' => [
            'iso_order_status_change'  => [
                'epost_recipient_fields' => ['billing_address_*', 'shipping_address_*'],
                'epost_subject'          => &$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['isotope']['iso_order_status_change']['email_text'],
                'epost_cover_letter'     => &$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['isotope']['iso_order_status_change']['email_text'],
            ],
            'iso_memberaddress_change' => [
                'epost_recipient_fields' => ['address_*', 'address_old_*', 'member_*'],
                'epost_subject'          => &$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['isotope']['iso_memberaddress_change']['email_subject'],
                'epost_cover_letter'     => &$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE']['isotope']['iso_memberaddress_change']['email_subject'],
            ],
        ],
    ]
);
