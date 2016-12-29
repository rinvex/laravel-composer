<?php

/*
 * NOTICE OF LICENSE
 *
 * Part of the Rinvex Composer Package.
 *
 * This source file is subject to The MIT License (MIT)
 * that is bundled with this package in the LICENSE file.
 *
 * Package: Rinvex Composer Package
 * License: The MIT License (MIT)
 * Link:    https://rinvex.com
 */

namespace Rinvex\Composer\Installers;

use Composer\Package\PackageInterface;

class CustomInstaller extends LibraryInstaller
{
    /**
     * {@inheritdoc}
     */
    public function supports($packageType)
    {
        return $packageType === 'cortex-custom';
    }

    /**
     * {@inheritdoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        $rootExtra = $this->composer->getPackage()->getExtra();

        $packageName = $package->getName();

        if (isset($rootExtra['paths'][$packageName])) {
            return $rootExtra['paths'][$packageName];
        }

        $packageExtra = $package->getExtra();

        if (isset($packageExtra['path'])) {
            return $packageExtra['path'];
        }
    }
}
