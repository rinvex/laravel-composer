<?php

declare(strict_types=1);

namespace Rinvex\Composer\Models;

use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class Plugin implements PluginInterface
{
    /**
     * The composer installer instances.
     *
     * @var \Rinvex\Composer\Models\Installer[]
     */
    protected $installers;

    /**
     * The composer extension installer instance.
     *
     * @var \Rinvex\Composer\Models\Installer
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
        foreach (Config::getKeys() as $type) {
            $this->installers[] = ($installer = new Installer($io, $composer, $type));
            $composer->getInstallationManager()->addInstaller($installer);
        }
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
        foreach ($this->installers as $installer) {
            $composer->getInstallationManager()->removeInstaller($installer);
        }
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
