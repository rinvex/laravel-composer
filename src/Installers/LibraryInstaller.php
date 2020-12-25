<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

use Composer\Package\PackageInterface;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Installer\LibraryInstaller as BaseLibraryInstaller;

class LibraryInstaller extends BaseLibraryInstaller
{
    /**
     * Paths array.
     *
     * @var array
     */
    protected $paths = [
        'base' => '/../../../../../',
        'app' => '/../../../../../app/',
        'bootstrap' => '/../../../../../bootstrap/',
    ];

    /**
     * Get config options.
     *
     * @return array
     */
    public function getConfig(): array
    {
        $vendorComposerConfig = __DIR__.'/../../config/config.php';
        $appComposerConfig = __DIR__.'/../../../../../config/rinvex.composer.php';

        return is_file($appComposerConfig) ? require $appComposerConfig
            : (is_file($vendorComposerConfig) ? require $vendorComposerConfig : []);
    }

    /**
     * Return path of the given dir.
     *
     * @param string $dir
     *
     * @return string
     */
    public function getPath(string $dir): string
    {
        return $this->getConfig()['paths'][$dir]
               ?? __DIR__.$this->paths[$dir];
    }

    /**
     * {@inheritdoc}
     */
    public function isInstalled(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        return parent::isInstalled($repo, $package);
    }

    /**
     * Installs specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param PackageInterface             $package package instance
     *
     * @throws \Exception
     *
     * @return void
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);
    }

    /**
     * Updates specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param PackageInterface             $initial already installed package version
     * @param PackageInterface             $target  updated version
     *
     * @throws \InvalidArgumentException if $initial package is not installed
     *
     * @return void
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        parent::update($repo, $initial, $target);
    }

    /**
     * Uninstalls specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param PackageInterface             $package package instance
     *
     * @throws \Exception
     *
     * @return void
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::uninstall($repo, $package);
    }
}
