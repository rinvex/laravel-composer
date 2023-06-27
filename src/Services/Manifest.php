<?php

declare(strict_types=1);

namespace Rinvex\Composer\Services;

use Exception;

class Manifest
{
    /**
     * Modules manifest content.
     *
     * @var array
     */
    protected $content = [];

    /**
     * Modules manifest path.
     *
     * @var string
     */
    protected $path;

    /**
     * Create a new Manifest instance.
     *
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * Load manifest from cache.
     *
     * @return array
     */
    public function retrieveContent()
    {
        if (is_file($this->path)) {
            $this->content = require $this->path;
        }

        return $this->content;
    }

    /**
     * Load manifest from cache.
     *
     * @return static
     */
    public function load()
    {
        $this->invalidateOpcache();

        $this->retrieveContent();

        return $this;
    }

    /**
     * Add given module to manifest file.
     *
     * @param string $moduleName
     * @param array  $moduleAttributes
     * @param bool   $override
     *
     * @return static
     */
    public function add(string $moduleName, array $moduleAttributes, bool $override = false)
    {
        if (! array_key_exists($moduleName, $this->content) || $override) {
            $this->content[$moduleName] = $moduleAttributes;
        }

        return $this;
    }

    /**
     * Get given module from manifest file.
     *
     * @param string $moduleName
     *
     * @return array|null
     */
    public function get(string $moduleName)
    {
        if (! array_key_exists($moduleName, $this->content)) {
            return;
        }

        return $this->content[$moduleName];
    }

    /**
     * Remove given module from manifest file.
     *
     * @return static
     */
    public function remove(string $moduleName)
    {
        if (array_key_exists($moduleName, $this->content)) {
            unset($this->content[$moduleName]);
        }

        return $this;
    }

    /**
     * Persist the given module manifest.
     *
     * @throws \Exception
     *
     * @return void
     */
    public function persist(): void
    {
        if (! is_writable($dirname = dirname($this->path))) {
            throw new Exception("The {$dirname} directory must be present and writable.");
        }

        // If the path already exists and is a symlink, get the real path...
        clearstatcache(true, $this->path);

        $this->path = realpath($this->path) ?: $this->path;

        $tempPath = tempnam(dirname($this->path), basename($this->path));

        // Fix permissions of tempPath because `tempnam()` creates it with permissions set to 0600...
        chmod($tempPath, 0777 - umask());

        file_put_contents($tempPath, '<?php return '.var_export($this->content, true).';');

        rename($tempPath, $this->path);

        $this->invalidateOpcache();
    }

    /**
     * Invalidate opcache for modules manifest file.
     *
     * @return void
     */
    protected function invalidateOpcache(): void
    {
        if (function_exists('opcache_invalidate')) {
            // Invalidate opcache of modules manifest file if exists
            @opcache_invalidate($this->path, true);
        }
    }
}
