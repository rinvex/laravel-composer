<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

use Illuminate\Support\Arr;
use React\Promise\PromiseInterface;
use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;

class ModuleInstaller extends LibraryInstaller
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
        return $packageType === $this->type && in_array($packageType, array_keys($this->config));
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
        return $this->laravel->joinPaths(Arr::get($this->config, $package->getType().'.path'), $package->getPrettyName());
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
        return in_array($package->getPrettyName(), Arr::get($this->config, $package->getType().'.always_active'));
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
                ? ['active' => $isAlwaysActive ? true : false, 'autoload' => $isAlwaysActive ? true : false, 'version' => $package->getPrettyVersion(), 'extends' => is_array($extra = $package->getExtra()) ? ($extra['cortex']['extends'] ?? null) : null]
                : ['active' => $isAlwaysActive ? true : false, 'autoload' => $isAlwaysActive ? true : false, 'version' => $package->getPrettyVersion()];

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
            $initialModule = $initial->getPrettyName();
            $targetModule = $target->getPrettyName();
            $isAlwaysActive = $this->isAlwaysActive($targetModule);
            $moduleExtends = is_array($extra = $target->getExtra()) ? ($extra['cortex']['extends'] ?? null) : null;

            $targetModuleAttributes = ['active' => $isAlwaysActive ? true : false, 'autoload' => $isAlwaysActive ? true : false, 'version' => $target->getPrettyVersion(), 'extends' => $moduleExtends];

            $this->manifest->load()->remove($initialModule)->persist();
            $this->manifest->load()->add($targetModule, $targetModuleAttributes)->persist();
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
