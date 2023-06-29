<?php

declare(strict_types=1);

namespace Rinvex\Composer\Models;

use Illuminate\Support\Arr;

class Config
{
    /**
     * Cache for the configuration array.
     *
     * @var array|null
     */
    protected static $configCache;

    /**
     * Get configuration options.
     *
     * @return array
     */
    public static function getOptions(): array
    {
        // If the configuration is already cached, return it
        if (self::$configCache !== null) {
            return self::$configCache;
        }

        // Otherwise, load the configuration from file
        $vendorConfig = __DIR__.'/../../config/config.php';
        $appConfig = app()->configPath('rinvex.composer.php');

        // Cache and return the loaded configuration
        return self::$configCache = is_file($appConfig) ? require $appConfig : (is_file($vendorConfig) ? require $vendorConfig : []);
    }

    /**
     * Get configuration keys.
     *
     * @return array
     */
    public static function getKeys(): array
    {
        $config = self::getOptions();

        return array_keys($config);
    }

    /**
     * Get a specific configuration value.
     *
     * @param string $key     The configuration key.
     * @param mixed  $default The default value if the configuration key does not exist.
     *
     * @return mixed The configuration value.
     */
    public static function get(string $key, $default = null)
    {
        $config = self::getOptions();

        return Arr::get($config, $key, $default);
    }
}
