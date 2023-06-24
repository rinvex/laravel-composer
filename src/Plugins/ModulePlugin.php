<?php

declare(strict_types=1);

namespace Rinvex\Composer\Plugins;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Rinvex\Composer\Installers\ModuleInstaller;

class ModulePlugin implements PluginInterface
{
    /**
     * The composer module installer instance.
     *
     * @var \Rinvex\Composer\Installers\ModuleInstaller
     */
    protected $moduleInstaller;

    /**
     * The composer extension installer instance.
     *
     * @var \Rinvex\Composer\Installers\ModuleInstaller
     */
    protected $extensionInstaller;

    /**
     * Apply plugin modifications to Composer.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     *
     * @throws \Exception
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->moduleInstaller = new ModuleInstaller($io, $composer, 'cortex-module');
        $this->extensionInstaller = new ModuleInstaller($io, $composer, 'cortex-extension');

        $composer->getInstallationManager()->addInstaller($this->moduleInstaller);
        $composer->getInstallationManager()->addInstaller($this->extensionInstaller);
    }

    /**
     * Remove any hooks from Composer.
     *
     * This will be called when a plugin is deactivated before being
     * uninstalled, but also before it gets upgraded to a new version
     * so the old one can be deactivated and the new one activated.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        $composer->getInstallationManager()->removeInstaller($this->moduleInstaller);
        $composer->getInstallationManager()->removeInstaller($this->extensionInstaller);
    }

    /**
     * Prepare the plugin to be uninstalled.
     *
     * This will be called after deactivate.
     *
     * @param Composer    $composer
     * @param IOInterface $io
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
        //
    }
}
