<?php

declare(strict_types=1);

namespace Rinvex\Composer\Models;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use Composer\Package\PackageInterface;
use Composer\Installer\BinaryInstaller;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;

class Installer extends LibraryInstaller
{
    /**
     * Module manifest instance.
     *
     * @var \Rinvex\Composer\Models\Manifest
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

        return Config::get($package->getType().'.path').($path !== '' ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : '');
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

            switch ($package->getType()) {
                case 'cortex-theme':
                    $attributes = [
                        'active' => $isAlwaysActive,
                        'version' => $package->getPrettyVersion(),
                    ];
                    break;
                case 'cortex-extension':
                    $attributes = [
                        'active' => $isAlwaysActive,
                        'autoload' => $isAlwaysActive,
                        'version' => $package->getPrettyVersion(),
                        'extends' => is_array($extra = $package->getExtra()) ? ($extra['cortex']['extends'] ?? null) : null,
                    ];
                    break;
                case 'cortex-module':
                default:
                    $attributes = [
                        'active' => $isAlwaysActive,
                        'autoload' => $isAlwaysActive,
                        'version' => $package->getPrettyVersion(),
                    ];
                    break;
            }

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
            $isAlwaysActive = $this->isAlwaysActive($target);
            $initialModule = $this->manifest->get($target->getPrettyName());

            switch ($target->getType()) {
                case 'cortex-theme':
                    $targetModuleAttributes = [
                        'active' => $isAlwaysActive,
                        'version' => $target->getPrettyVersion(),
                    ];
                    break;
                case 'cortex-extension':
                    $targetModuleAttributes = [
                        'active' => $initialModule['active'] ?? $isAlwaysActive,
                        'autoload' => $initialModule['autoload'] ?? $isAlwaysActive,
                        'version' => $target->getPrettyVersion(),
                        'extends' => is_array($extra = $target->getExtra()) ? ($extra['cortex']['extends'] ?? null) : null
                    ];
                    break;
                case 'cortex-module':
                default:
                    $targetModuleAttributes = [
                        'active' => $initialModule['active'] ?? $isAlwaysActive,
                        'autoload' => $initialModule['autoload'] ?? $isAlwaysActive,
                        'version' => $target->getPrettyVersion()
                    ];
                    break;
            }

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
