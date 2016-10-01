<?php
/**
 * E-POSTBUSINESS API integration for Contao Open Source CMS
 * Copyright (c) 2015-2016 Richard Henkenjohann
 * @package E-POST
 * @author  Richard Henkenjohann <richard-epost@henkenjohann.me>
 */


/**
 * Notification Center Gateways
 */
$GLOBALS['NOTIFICATION_CENTER']['GATEWAY']['epost'] = 'NotificationCenter\Gateway\EPost';


/**
 * Notification Center Notification Types
 */
$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'] = array_merge_recursive(
    (array)$GLOBALS['NOTIFICATION_CENTER']['NOTIFICATION_TYPE'],
    [
        'contao' => [
            'member_registration' => [
                'epost_recipient_fields' => ['member_*'],
                'epost_subject'          => ['member_*', 'domain'],
                'epost_cover_letter'     => ['domain', 'link', 'member_*', 'admin_email'],
            ],
        ],
    ]
);
