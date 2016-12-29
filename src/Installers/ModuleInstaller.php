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

use Rinvex\Module\Module;
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
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);

        if ($package->getExtra()['cortex-module']['autoinstall']) {
            require_once $this->getPath('bootstrap').'/autoload.php';

            $app = require_once $this->getPath('bootstrap').'/app.php';

            $app->make(Kernel::class)->bootstrap();

            (new Module($app, ['name' => $package->getPrettyName(), 'autoload' => $package->getAutoload()]))->install();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        if ($package->getExtra()['cortex-module']['autoinstall']) {
            require_once $this->getPath('bootstrap').'/autoload.php';

            $app = require_once $this->getPath('bootstrap').'/app.php';

            $app->make(Kernel::class)->bootstrap();

            (new Module($app, ['name' => $package->getPrettyName(), 'autoload' => $package->getAutoload()]))->uninstall();
        }

        parent::uninstall($repo, $package);
    }
}
