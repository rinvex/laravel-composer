<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

use Composer\Composer;
use Illuminate\Support\Arr;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use React\Promise\PromiseInterface;
use Rinvex\Composer\Services\Config;
use Illuminate\Foundation\Application;
use Composer\Package\PackageInterface;
use Composer\Installer\BinaryInstaller;
use Composer\Installer\LibraryInstaller;
use Rinvex\Composer\Services\Manifest;
use Composer\Repository\InstalledRepositoryInterface;

class ModuleInstaller extends LibraryInstaller
{
    /**
     * Module manifest instance.
     *
     * @var \Rinvex\Composer\Services\Manifest
     */
    public $manifest;

    /**
     * Laravel application instance.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $laravel;

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

        $this->manifest = new Manifest(Config::get($type.'.manifest'));
    }

    /**
     * Decides if the installer supports the given type.
     *
     * @param string $packageType
     *
     * @return bool
     */
    public function supports($packageType)
    {
        return $packageType === $this->type && in_array($packageType, Config::getKeys());
    }

    /**
     * Returns the installation path of a package.
     *
     * @param \Composer\Package\PackageInterface $package
     *
     * @return string
     */
    public function getInstallPath(PackageInterface $package)
    {
        $path = $package->getPrettyName();

        return Config::get($package->getType().'.path').($path != '' ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : '');
    }

    /**
     * Check if given package is always active or not.
     *
     * @param \Composer\Package\PackageInterface $package
     *
     * @return bool
     */
    protected function isAlwaysActive(PackageInterface $package): bool
    {
        return in_array($package->getPrettyName(), Config::get($package->getType().'.always_active'));
    }

    /**
     * Installs specific package.
     *
     * @param InstalledRepositoryInterface       $repo    repository in which to check
     * @param \Composer\Package\PackageInterface $package package instance
     *
     * @throws \Exception
     *
     * @return void
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $promise = parent::install($repo, $package);

        return $promise->then(function () use ($package) {
            $isAlwaysActive = $this->isAlwaysActive($package);

            $attributes = $package->getType() === 'cortex-extension'
                ? ['active' => $isAlwaysActive, 'autoload' => $isAlwaysActive, 'version' => $package->getPrettyVersion(), 'extends' => is_array($extra = $package->getExtra()) ? ($extra['cortex']['extends'] ?? null) : null]
                : ['active' => $isAlwaysActive, 'autoload' => $isAlwaysActive, 'version' => $package->getPrettyVersion()];

            $this->manifest->load()->add($package->getPrettyName(), $attributes)->persist();
        });
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
     *
     * @return void
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        $promise = parent::update($repo, $initial, $target);

        return $promise->then(function () use ($initial, $target) {
            $initialModule = $this->manifest->get($target->getPrettyName());
            $isAlwaysActive = $this->isAlwaysActive($target->getPrettyName());

            $targetModuleAttributes = $target->getType() === 'cortex-extension'
                ? ['active' => $initialModule['active'] ?? $isAlwaysActive, 'autoload' => $initialModule['autoload'] ?? $isAlwaysActive, 'version' => $target->getPrettyVersion(), 'extends' => is_array($extra = $target->getExtra()) ? ($extra['cortex']['extends'] ?? null) : null]
                : ['active' => $initialModule['active'] ?? $isAlwaysActive, 'autoload' => $initialModule['autoload'] ?? $isAlwaysActive, 'version' => $target->getPrettyVersion()];

            $this->manifest->load()->remove($initial->getPrettyName())->persist();
            $this->manifest->load()->add($target->getPrettyName(), $targetModuleAttributes)->persist();
        });
}

    /**
     * Uninstalls specific package.
     *
     * @param InstalledRepositoryInterface       $repo    repository in which to check
     * @param \Composer\Package\PackageInterface $package package instance
     *
     * @throws \Exception
     *
     * @return void
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $promise = parent::uninstall($repo, $package);

        return $promise->then(function () use ($package) {
            $this->manifest->load()->remove($package->getPrettyName())->persist();
        });
    }
}
