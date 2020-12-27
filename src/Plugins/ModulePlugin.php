<?php

declare(strict_types=1);

namespace Rinvex\Composer\Plugins;

use Composer\Composer;
use Composer\DependencyResolver\Operation\UpdateOperation;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\IOInterface;
use Composer\Package\Version\VersionParser;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Illuminate\Foundation\Application;
use Rinvex\Composer\Installers\ModuleInstaller;

class ModulePlugin implements PluginInterface, EventSubscriberInterface
{
    /**
     * The composer installer instance.
     *
     * @var \Rinvex\Composer\Installers\ModuleInstaller
     */
    protected $installer;

    /**
     * Module updates array, that holds old and new versions being updated.
     *
     * @var array noted module updates.
     */
    protected $moduleUpdates = [];

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
        $this->installer = new ModuleInstaller($io, $composer);

        $composer->getInstallationManager()->addInstaller($this->installer);

        $this->installer->manifest->load()->persist();
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
        $composer->getInstallationManager()->removeInstaller($this->installer);
    }

    /**
     * Prepare the plugin to be uninstalled
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

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     * * The method name to call (priority defaults to 0)
     * * An array composed of the method name to call and the priority
     * * An array of arrays composed of the method names to call and respective
     *   priorities, or 0 if unset.
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::POST_PACKAGE_UPDATE => 'checkModuleUpdates',
            ScriptEvents::POST_UPDATE_CMD => 'showUpgradeNotes',
        ];
    }

    /**
     * Listen to POST_PACKAGE_UPDATE event and take note of the module updates.
     *
     * @param \Composer\Installer\PackageEvent $event
     *
     * @return void
     */
    public function checkModuleUpdates(PackageEvent $event): void
    {
        $operation = $event->getOperation();

        if ($operation instanceof UpdateOperation) {
            $this->moduleUpdates[$operation->getInitialPackage()->getName()] = [
                'from' => $operation->getInitialPackage()->getVersion(),
                'fromPretty' => $operation->getInitialPackage()->getPrettyVersion(),
                'to' => $operation->getTargetPackage()->getVersion(),
                'toPretty' => $operation->getTargetPackage()->getPrettyVersion(),
                'direction' => $this->isUpgrade($event, $operation) ? 'up' : 'down',
            ];
        }
    }

    /**
     * Check if the operation is an upgrade.
     *
     * @param \Composer\Installer\PackageEvent $event
     * @param \Composer\DependencyResolver\Operation\UpdateOperation $operation
     *
     * @return bool
     */
    protected function isUpgrade(PackageEvent $event, UpdateOperation $operation)
    {
        // Composer 1.7.0+
        if (method_exists('Composer\Package\Version\VersionParser', 'isUpgrade')) {
            return VersionParser::isUpgrade(
                $operation->getInitialPackage()->getVersion(),
                $operation->getTargetPackage()->getVersion()
            );
        }

        return $event->getPolicy()->versionCompare(
            $operation->getInitialPackage(),
            $operation->getTargetPackage(),
            '<'
        );
    }

    /**
     * Listen to POST_UPDATE_CMD event to display information about upgrade notes if appropriate.
     *
     * @param \Composer\Script\Event $event
     */
    public function showUpgradeNotes(Event $event)
    {
        foreach ($this->installer->getConfig('core_modules') as $moduleName) {
            if (! isset($this->moduleUpdates[$moduleName])) {
                return;
            }

            $module = $this->moduleUpdates[$moduleName];

            // Don't show a notice on up/downgrades between dev versions
            // avoid messages like from version dev-master to dev-master
            if ($module['fromPretty'] === $module['toPretty']) {
                return;
            }

            $io = $event->getIO();

            // Print the relevant upgrade notes for the upgrade
            // - only on upgrade, not on downgrade
            // - only if the "from" version is non-dev, otherwise we have no idea which notes to show
            if ($module['direction'] === 'up' && $this->isNumericVersion($module['fromPretty'])) {
                $notes = $this->findUpgradeNotes($moduleName, $module['fromPretty']);

                // No relevant upgrade notes, do not show anything.
                if ($notes !== false && empty($notes)) {
                    return;
                }

                $this->printUpgradeIntro($io, $module);

                if ($notes) {
                    // Safety check: do not display notes if they are too many
                    if (count($notes) > 250) {
                        $io->write("\n  <fg=yellow;options=bold>The relevant notes for your upgrade are too long to be displayed here.</>");
                    } else {
                        $io->write("\n  ".trim(implode("\n  ", $notes)));
                    }
                }

                $io->write("\n  You can find the upgrade notes for all versions online at:");
            } else {
                $this->printUpgradeIntro($io, $module);
                $io->write("\n  You can find the upgrade notes online at:");
            }

            $this->printUpgradeLink($io, $module, $moduleName);
        }
    }

    /**
     * Print link to upgrade notes.
     *
     * @param IOInterface $io
     * @param array $module
     * @param string $moduleName
     */
    protected function printUpgradeLink($io, $module, $moduleName)
    {
        $maxVersion = $module['direction'] === 'up' ? $module['toPretty'] : $module['fromPretty'];

        // make sure to always show a valid link, even if $maxVersion is something like dev-master
        if (!$this->isNumericVersion($maxVersion)) {
            $maxVersion = 'master';
        }

        $io->write("  https://github.com/{$moduleName}/blob/{$maxVersion}/UPGRADE.md\n");
    }

    /**
     * Print upgrade intro
     * @param IOInterface $io
     * @param array $module
     *
     * @return void
     */
    protected function printUpgradeIntro($io, $module): void
    {
        $io->write("\n  <fg=yellow;options=bold>Seems you have "
            . ($module['direction'] === 'up' ? 'upgraded' : 'downgraded')
            . ' Cortex module from version '
            . $module['fromPretty'] . ' to ' . $module['toPretty'] . '.</>'
        );
        $io->write("\n  <options=bold>Please check the upgrade notes for possible incompatible changes");
        $io->write('  and adjust your application code accordingly.</>');
    }

    /**
     * Read upgrade notes from a files and returns an array of lines.
     *
     * @param string $moduleName module name
     * @param string $fromVersion until which version to read the notes.
     *
     * @return array|false
     */
    protected function findUpgradeNotes($moduleName, $fromVersion)
    {
        if (preg_match('/^([0-9]\.[0-9]+\.?[0-9]*)/', $fromVersion, $majorVersionMatches)) {
            $fromVersionMajor = $majorVersionMatches[1];
        } else {
            $fromVersionMajor = $fromVersion;
        }

        $upgradeFile = $this->installer->getModulesPath($moduleName).'/UPGRADE.md';

        if (! is_file($upgradeFile) || ! is_readable($upgradeFile)) {
            return false;
        }

        $lines = preg_split('~\R~', file_get_contents($upgradeFile));
        $relevantLines = [];
        $consuming = false;

        // whether an exact match on $fromVersion has been encountered
        $foundExactMatch = false;
        foreach ($lines as $line) {
            if (preg_match('/^Upgrade from ([0-9]\.[0-9]+\.?[0-9\.]*)/i', $line, $matches)) {
                if ($matches[1] === $fromVersion) {
                    $foundExactMatch = true;
                }

                if (version_compare($matches[1], $fromVersion, '<') && ($foundExactMatch || version_compare($matches[1], $fromVersionMajor, '<'))) {
                    break;
                }

                $consuming = true;
            }

            if ($consuming) {
                $relevantLines[] = $line;
            }
        }

        return $relevantLines;
    }

    /**
     * Check whether a version is numeric, e.g. 2.0.10.
     *
     * @param string $version
     *
     * @return bool
     */
    protected function isNumericVersion($version): bool
    {
        return (bool) preg_match('~^([0-9]\.[0-9]+\.?[0-9\.]*)~', $version);
    }
}
