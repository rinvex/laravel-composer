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
        'base'      => '/../../../../../',
        'app'       => '/../../../../../app/',
        'bootstrap' => '/../../../../../bootstrap/',
    ];

    /**
     * Return the path.
     *
     * @param string $path
     *
     * @return string
     */
    protected function getPath($path)
    {
        $pathsFile = __DIR__.'/../../../../../config/rinvex.composer.php';

        if (file_exists($pathsFile)) {
            $paths = require $pathsFile;
        }

        return isset($paths[$path]) ? $paths[$path] : __DIR__.$this->paths[$path];
    }

    /**
     * {@inheritDoc}
     */
    public function isInstalled(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        return parent::isInstalled($repo, $package);
    }

    /**
     * {@inheritDoc}
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::install($repo, $package);
    }

    /**
     * {@inheritDoc}
     */
    public function update(InstalledRepositoryInterface $repo, PackageInterface $initial, PackageInterface $target)
    {
        parent::update($repo, $initial, $target);
    }

    /**
     * {@inheritDoc}
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        parent::uninstall($repo, $package);
    }
}
