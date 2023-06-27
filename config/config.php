<?php

declare(strict_types=1);

return [

    'cortex-module' => [
        'path' => app()->path('modules'),
        // We're not using `app()->getCachedModulesPath()` approach because it requires
        // modifying the `Application::class` which is not within the scope of this package.
        'manifest' => app()->bootstrapPath('cache'.DIRECTORY_SEPARATOR.'modules.php'),

        'always_active' => [
            'cortex/foundation',
            'cortex/auth',
        ],
    ],

    'cortex-extension' => [
        'path' => app()->path('extensions'),
        'manifest' => app()->bootstrapPath('cache'.DIRECTORY_SEPARATOR.'extensions.php'),

        'always_active' => [],
    ],

];
