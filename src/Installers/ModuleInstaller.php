<?php

declare(strict_types=1);

namespace Rinvex\Composer\Installers;

use Exception;
use Composer\Package\PackageInterface;
use Illuminate\Foundation\Application;
use Composer\Repository\InstalledRepositoryInterface;

class ModuleInstaller extends LibraryInstaller
{
    /**
     * Decides if the installer supports the given type
     *
     * @param  string $packageType
     *
     * @return bool
     */
    public function supports($packageType)
    {
        return $packageType === 'cortex-module';
    }

    /**
     * Returns the installation path of a package
     *
     * @param  PackageInterface $package
     *
     * @return string
     */
    public function getInstallPath(PackageInterface $package)
    {
        return $this->getPath('app').$package->getPrettyName();
    }

    /**
     * Installs specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param PackageInterface             $package package instance
     *
     * @throws \Exception
     *
     * @return void
     */
    public function install(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->loadModule($package, true);

        parent::install($repo, $package);
    }

    /**
     * Uninstalls specific package.
     *
     * @param InstalledRepositoryInterface $repo    repository in which to check
     * @param PackageInterface             $package package instance
     *
     * @throws \Exception
     *
     * @return void
     */
    public function uninstall(InstalledRepositoryInterface $repo, PackageInterface $package)
    {
        $this->loadModule($package, false);

        parent::uninstall($repo, $package);
    }

    /**
     * Autoload installed module.
     *
     * @param \Composer\Package\PackageInterface $package
     * @param bool                               $status
     *
     * @throws \Exception
     *
     * @return void
     */
    protected function loadModule(PackageInterface $package, bool $status): void
    {
        $laravel = new Application(getcwd());
        $modulesManifestPath = $laravel->bootstrapPath('cache'.DIRECTORY_SEPARATOR.'modules.php');
        $modulesManifest = file_exists($modulesManifestPath) ? require $modulesManifestPath : [];

        if ($status && ! in_array($package->getPrettyName(), array_keys($modulesManifest))) {
            $modulesManifest = array_merge($modulesManifest, [$package->getPrettyName() => ['active' => false, 'autoload' => true]]);
        } else if (! $status) {
            unset($modulesManifest[$package->getPrettyName()]);
        }

        if (! is_writable($dirname = dirname($modulesManifestPath))) {
            throw new Exception("The {$dirname} directory must be present and writable.");
        }

        $this->replaceFile(
            $modulesManifestPath, '<?php return '.var_export($modulesManifest, true).';'
        );
    }

    /**
     * Write the contents of a file, replacing it atomically if it already exists.
     *
     * @param  string  $path
     * @param  string  $content
     *
     * @return void
     */
    protected function replaceFile($path, $content)
    {
        // If the path already exists and is a symlink, get the real path...
        clearstatcache(true, $path);

        $path = realpath($path) ?: $path;

        $tempPath = tempnam(dirname($path), basename($path));

        // Fix permissions of tempPath because `tempnam()` creates it with permissions set to 0600...
        chmod($tempPath, 0777 - umask());

        file_put_contents($tempPath, $content);

        rename($tempPath, $path);
    }
}
