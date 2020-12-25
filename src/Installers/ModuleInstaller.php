<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use React\Promise\PromiseInterface;
use Composer\Package\PackageInterface;
use Illuminate\Foundation\Application;
use Composer\Installer\BinaryInstaller;
use Rinvex\Composer\Services\ModuleManifest;
use Composer\Repository\InstalledRepositoryInterface;

class ModuleInstaller extends LibraryInstaller
{
    /**
     * Module manifest instance.
     *
     * @var \Rinvex\Composer\Services\ModuleManifest
     */
    public $manifest;

    /**
     * Initializes library installer.
     *
     * @param IOInterface     $io
     * @param Composer        $composer
     * @param string          $type
     * @param Filesystem      $filesystem
     * @param BinaryInstaller $binaryInstaller
     */
    public function __construct(IOInterface $io, Composer $composer, $type = 'library', Filesystem $filesystem = null, BinaryInstaller $binaryInstaller = null)
    {
        parent::__construct($io, $composer, $type, $filesystem, $binaryInstaller);

        $laravel = new Application(getcwd());
        $modulesManifestPath = $laravel->bootstrapPath('cache'.DIRECTORY_SEPARATOR.'modules.php');
        $this->manifest = new ModuleManifest($modulesManifestPath);
    }

    /**
     * Decides if the installer supports the given type
     *
     * @param  string $packageType
     *
     * @return bool
     */
    public function supports($packageType)
    {
        return $packageType === 'cortex-module';
    }

    /**
     * Returns the installation path of a package
     *
     * @param  \Composer\Package\PackageInterface $package
     *
     * @return string
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->getPath('app').$package->getPrettyName();
    }

    /**
     * Installs specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param \Composer\Package\PackageInterface             $package package instance
     *
     * @throws \Exception
     *
     * @return void
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $afterInstall = function () use ($package) {
            $module = $package->getPrettyName();
            $isCore = $this->isCore($module);

            $attributes = ['active' => $isCore ? true : false, 'autoload' => $isCore ? true : false, 'version' => $package->getVersion()];
            $this->manifest->load()->add($module, $attributes)->persist();
        };

        // Install module, the normal composer way
        $promise = parent::install($repo, $package);

        // Composer v2 might return a promise
        if ($promise instanceof PromiseInterface) {
            return $promise->then($afterInstall);
        }

        // If not, execute the code right away as parent::install
        // executed synchronously (composer v1, or v2 without async)
        $afterInstall();
    }

    /**
     * Check if given module is core or not.
     *
     * @param string $module
     *
     * @return bool
     */
    protected function isCore(string $module): bool
    {
        return in_array($module, $this->getConfig()['core']);
    }

    /**
     * Updates specific package.
     *
     * @param InstalledRepositoryInterface       $repo    repository in which to check
     * @param \Composer\Package\PackageInterface $initial already installed package version
     * @param PackageInterface                   $target  updated version
     *
     * @throws \Exception
     * @throws \InvalidArgumentException if $initial package is not installed
     * @return void
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        $afterUpdate = function () use ($initial, $target) {
            $initialModule = $initial->getPrettyName();
            $targetModule = $target->getPrettyName();
            $isCore = $this->isCore($targetModule);

            $targetModuleAttributes = ['active' => $isCore ? true : false, 'autoload' => $isCore ? true : false, 'version' => $target->getVersion(),];

            $this->manifest->load()->remove($initialModule)->persist();
            $this->manifest->load()->add($targetModule, $targetModuleAttributes)->persist();
        };

        // Update module, the normal composer way
        $promise = parent::update($repo, $initial, $target);

        // Composer v2 might return a promise
        if ($promise instanceof PromiseInterface) {
            return $promise->then($afterUpdate);
        }

        // If not, execute the code right away as parent::update
        // executed synchronously (composer v1, or v2 without async)
        $afterUpdate();
    }

    /**
     * Uninstalls specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param \Composer\Package\PackageInterface             $package package instance
     *
     * @throws \Exception
     *
     * @return void
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $afterUninstall = function () use ($package) {
            $module = $package->getPrettyName();
            $this->manifest->load()->remove($module)->persist();
        };

        // Uninstall the package the normal composer way
        $promise = parent::uninstall($repo, $package);

        // Composer v2 might return a promise
        if ($promise instanceof PromiseInterface) {
            return $promise->then($afterUninstall);
        }

        // If not, execute the code right away as parent::uninstall
        // executed synchronously (composer v1, or v2 without async)
        $afterUninstall();
    }
}
