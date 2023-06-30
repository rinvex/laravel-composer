<?php

declare(strict_types=1);

$rootPath = realpath(__DIR__.'/../../../../');

return [

    'cortex-module' => [
        'path' => env('APP_MODULES_PATH', "{$rootPath}/app/modules"),
        'manifest' => env('APP_MODULES_CACHE', "{$rootPath}/bootstrap/cache/modules.php"),

        'always_active' => [
            'cortex/foundation',
            'cortex/auth',
        ],
    ],

    'cortex-extension' => [
        'path' => env('APP_EXTENSIONS_PATH', "{$rootPath}/app/extensions"),
        'manifest' => env('APP_EXTENSIONS_CACHE', "{$rootPath}/bootstrap/cache/extensions.php"),

        'always_active' => [],
    ],

];
