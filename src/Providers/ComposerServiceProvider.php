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
        $this->mergeConfigFrom(
            realpath(__DIR__.'/../../config/config.php'), 'rinvex.composer'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function boot()
    {
        // Publish config
        if ($this->app->runningInConsole()) {
            $this->publishes([
                realpath(__DIR__.'/../../config/config.php') => config_path('rinvex.composer.php'),
            ], 'config');
        }
    }
}
