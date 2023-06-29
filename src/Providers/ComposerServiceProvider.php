<?php

declare(strict_types=1);

namespace Rinvex\Composer\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Support\Traits\ConsoleTools;
use Rinvex\Composer\Console\Commands\PublishCommand;

class ComposerServiceProvider extends ServiceProvider
{
    use ConsoleTools;

    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        PublishCommand::class,
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.composer');

        // Register console commands
        $this->commands($this->commands);
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Register paths to be published by the publish command.
        $this->publishConfigFrom(__DIR__.'/../../config/config.php', 'rinvex/composer');

        // Get the paths to the module and extension cache files.
        $this->app->macro('getCachedModulesPath', fn () => $this->normalizeCachePath('APP_MODULES_CACHE', 'cache/modules.php'));
        $this->app->macro('getCachedExtensionsPath', fn () => $this->normalizeCachePath('APP_EXTENSIONS_CACHE', 'cache/extensions.php'));
    }
}
