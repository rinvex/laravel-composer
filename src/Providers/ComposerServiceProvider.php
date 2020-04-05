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
        $this->publishesConfig('rinvex/laravel-composer');
    }
}
