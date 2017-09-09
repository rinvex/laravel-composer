<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

use Rinvex\Modulable\Module;
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

        if ($package->getExtra()['cortex-module']['autoinstall']) {
            require_once $this->getPath('bootstrap').'/autoload.php';

            $app = require_once $this->getPath('bootstrap').'/app.php';

            $app->make(Kernel::class)->bootstrap();

            (new Module($app, ['name' => $package->getPrettyName(), 'autoload' => $package->getAutoload()]))->install();
        }
    }

    /**
     * {@inheritdoc}
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
