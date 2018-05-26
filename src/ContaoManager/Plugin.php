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

namespace Richardhj\ContaoEPostNotificationCenterBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Richardhj\ContaoEPostCoreBundle\RichardhjContaoEPostCoreBundle;
use Richardhj\ContaoEPostNotificationCenterBundle\RichardhjContaoEPostNotificationCenterBundle;

class Plugin implements BundlePluginInterface
{

    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(RichardhjContaoEPostNotificationCenterBundle::class)
                ->setLoadAfter(
                    [
                        ContaoCoreBundle::class,
                        RichardhjContaoEPostCoreBundle::class
                    ]
                ),
        ];
    }
}
