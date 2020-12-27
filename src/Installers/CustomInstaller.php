<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

use Composer\Package\PackageInterface;

class CustomInstaller extends LibraryInstaller
{
    /**
     * Decides if the installer supports the given type.
     *
     * @param string $packageType
     *
     * @return bool
     */
    public function supports($packageType)
    {
        return $packageType === 'cortex-custom';
    }

    /**
     * Returns the installation path of a package.
     *
     * @param PackageInterface $package
     *
     * @return string
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
