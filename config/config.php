<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Composer Installer Paths
    |--------------------------------------------------------------------------
    |
    | Here you can specify the installable paths for composer installer.
    |
    | CAUTION: YOU CAN NOT USE ANY LARAVEL FEATURES IN THIS CONFIG FILE!
    | This config file is loaded first thing at the very beginning
    | on every composer action before even any package
    | being installed. Just pure PHP acceptable!
    |
    | Paths are relative to the root config dir.
    |
    */

    'paths' => [
        'base' => __DIR__.'/../',
        'app' => __DIR__.'/../app/',
        'bootstrap' => __DIR__.'/../bootstrap/',
    ],

    'core' => ['cortex/foundation', 'cortex/auth'],

];
