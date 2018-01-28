<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

use Composer\Package\PackageInterface;
use Illuminate\Contracts\Console\Kernel;
use Composer\Repository\InstalledRepositoryInterface;

class ModuleInstaller extends LibraryInstaller
{
    /**
     * {@inheritdoc}
     */
    public function supports($packageType)
    {
        return $packageType === 'cortex-module';
    }

    /**
     * {@inheritdoc}
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->getPath('app').$package->getPrettyName();
    }

    /**
     * {@inheritdoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::uninstall($repo, $package);
    }
}
