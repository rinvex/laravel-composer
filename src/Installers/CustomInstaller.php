<?php

declare(strict_types=1);

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
