<?php

declare(strict_types=1);

return [

    'cortex-module' => [
        'path' => env('APP_MODULES_PATH', realpath(__DIR__.'/../../../../app/modules')),
        'manifest' => env('APP_MODULES_CACHE', realpath(__DIR__.'/../../../../bootstrap/cache/modules.php')),

        'always_active' => [
            'cortex/foundation',
            'cortex/auth',
        ],
    ],

    'cortex-extension' => [
        'path' => env('APP_EXTENSIONS_PATH', realpath(__DIR__.'/../../../../app/extensions')),
        'manifest' => env('APP_EXTENSIONS_CACHE', realpath(__DIR__.'/../../../../bootstrap/cache/extensions.php')),

        'always_active' => [],
    ],

];
