<?php

declare(strict_types=1);

return [

    'cortex-module' => [
        'path' => app()->path('modules'),
        'manifest' => app()->getCachedModulesPath(),

        'always_active' => [
            'cortex/foundation',
            'cortex/auth',
        ],
    ],

    'cortex-extension' => [
        'path' => app()->path('extensions'),
        'manifest' => app()->getCachedExtensionsPath(),

        'always_active' => [],
    ],

];
