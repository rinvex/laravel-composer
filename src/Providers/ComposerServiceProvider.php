<?php

declare(strict_types=1);

namespace Rinvex\Composer\Providers;

use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(realpath(__DIR__.'/../../config/config.php'), 'rinvex.composer');
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.composer.php')], 'rinvex-composer-config');
        }
    }
}
