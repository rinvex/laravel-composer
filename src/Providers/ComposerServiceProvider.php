<?php

declare(strict_types=1);

namespace Rinvex\Composer\Providers;

use Illuminate\Support\ServiceProvider;
use Rinvex\Composer\Console\Commands\PublishCommand;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * The commands to be registered.
     *
     * @var array
     */
    protected $commands = [
        PublishCommand::class => 'command.rinvex.composer.publish',
    ];

    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.composer');

        // Register console commands
        ! $this->app->runningInConsole() || $this->registerCommands();
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Publish Resources
        ! $this->app->runningInConsole() || $this->publishResources();
    }

    /**
     * Publish resources.
     *
     * @return void
     */
    protected function publishResources(): void
    {
        $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.composer.php')], 'rinvex-composer-config');
    }

    /**
     * Register console commands.
     *
     * @return void
     */
    protected function registerCommands(): void
    {
        // Register artisan commands
        foreach ($this->commands as $key => $value) {
            $this->app->singleton($value, $key);
        }

        $this->commands(array_values($this->commands));
    }
}
