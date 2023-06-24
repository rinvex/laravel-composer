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
     * Loaded config options.
     *
     * @var array
     */
    public $config;

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
        $this->config = $this->loadConfig();
        $this->manifest = new ModuleManifest(Arr::get($this->config, $type.'.manifest'));
        //$this->manifest->load()->persist();
    }

    /**
     * load config options.
     *
     * @return array
     */
    public function loadConfig()
    {
        $vendorConfig = __DIR__.'/../../config/config.php';
        $appConfig = $this->laravel->configPath().'/rinvex.composer.php';

        $config = is_file($appConfig) ? require $appConfig
            : (is_file($vendorConfig) ? require $vendorConfig : []);

        return $this->config = $config;
    }
}
