<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

use Composer\Composer;
use Illuminate\Support\Arr;
use Composer\IO\IOInterface;
use Composer\Util\Filesystem;
use Illuminate\Foundation\Application;
use Composer\Installer\BinaryInstaller;
use Rinvex\Composer\Services\ModuleManifest;
use Composer\Installer\LibraryInstaller as BaseLibraryInstaller;

class LibraryInstaller extends BaseLibraryInstaller
{
    /**
     * Module manifest instance.
     *
     * @var \Rinvex\Composer\Services\ModuleManifest
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

        $this->laravel = new Application(getcwd());
        $modulesManifestPath = $this->laravel->bootstrapPath('cache'.DIRECTORY_SEPARATOR.'modules.php');
        $this->manifest = new ModuleManifest($modulesManifestPath);
    }

    /**
     * Get config options.
     *
     * @param string|null $key
     *
     * @return mixed
     */
    public function getConfig(string $key = null)
    {
        $vendorConfig = __DIR__.'/../../config/config.php';
        $appConfig = $this->laravel->configPath().'/rinvex.composer.php';

        $config = is_file($appConfig) ? require $appConfig
            : (is_file($vendorConfig) ? require $vendorConfig : []);

        $config = Arr::dot($config);

        return ! is_null($key) && isset($config[$key]) ? $config[$key] : $config;
    }
}
