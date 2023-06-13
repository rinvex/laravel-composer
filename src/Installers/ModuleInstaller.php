<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

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
        return $packageType === 'cortex-module';
    }

    /**
     * Returns the installation path of a package.
     *
     * @return string
     */
    public function getModulesPath(string $module = null)
    {
        return $this->laravel->path($module);
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
        return $this->getModulesPath($package->getPrettyName());
    }

    /**
     * Check if given module is core or not.
     *
     * @param string $module
     *
     * @return bool
     */
    protected function isAlwaysActive(string $module): bool
    {
        return in_array($module, $this->getConfig('always_active'));
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
        $afterInstall = function () use ($package) {
            $module = $package->getPrettyName();
            $isAlwaysActive = $this->isAlwaysActive($module);
            $moduleExtends = (is_array($extra = $package->getExtra()) ? $extra['cortex-module']['extends'] : null) ?? null;

            $attributes = ['active' => $isAlwaysActive ? true : false, 'autoload' => $isAlwaysActive ? true : false, 'version' => $package->getPrettyVersion(), 'extends' => $moduleExtends];
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
        $afterUpdate = function () use ($initial, $target) {
            $initialModule = $initial->getPrettyName();
            $targetModule = $target->getPrettyName();
            $isAlwaysActive = $this->isAlwaysActive($targetModule);
            $moduleExtends = (is_array($extra = $target->getExtra()) ? $extra['cortex-module']['extends'] : null) ?? null;

            $targetModuleAttributes = ['active' => $isAlwaysActive ? true : false, 'autoload' => $isAlwaysActive ? true : false, 'version' => $target->getPrettyVersion(), 'extends' => $moduleExtends];

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
     * @param InstalledRepositoryInterface       $repo    repository in which to check
     * @param \Composer\Package\PackageInterface $package package instance
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
