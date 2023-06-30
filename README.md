# Rinvex Composer

**Rinvex Composer** is an intuitive package that utilizes Composer Plugin API to support additional actions during installation, such as installing packages outside of the default vendor library and running custom scripts during install, update, and uninstall processes.

[![Packagist](https://img.shields.io/packagist/v/rinvex/laravel-composer.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/laravel-composer)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/laravel-composer.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/laravel-composer/)
[![Travis](https://img.shields.io/travis/rinvex/laravel-composer.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/laravel-composer)
[![StyleCI](https://styleci.io/repos/77618130/shield)](https://styleci.io/repos/77618130)
[![License](https://img.shields.io/packagist/l/rinvex/laravel-composer.svg?label=License&style=flat-square)](https://github.com/rinvex/laravel-composer/blob/develop/LICENSE)

**Rinvex Composer** also handles any module specific install/uninstall logic, so if your custom **cortext-module** contains for example migrations and/or seeds, it will be automatically executed upon composer installation.


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/laravel-composer
    ```

2. **Optional** if you want to change the configurations:
    ```shell
    php artisan rinvex:publish:composer
    ```

3. Done!


## Usage

As it should be clear, the main purpose of this package is to modify composer's behaviour so packages of custom types could be installed in directories other than the default `vendor`. This is done by utilizing Composer Plugin API, and the package comes with a built-in plugin that handles the installation of Rinvex Cortex modules and extensions.

### Supported Package Types

- cortex-module
- cortex-extension

> **Notes:**
> - Checkout Composer's [Custom Installers](https://github.com/composer/composer/blob/master/doc/articles/custom-installers.md)
> - Additional custom package types can be added through the config file. The config file is published to `config/rinvex.composer.php` and contains detailed examples custom package types.

### Basic Usage

Rinvex Cortex modules and extensions are installed into the configurable paths, so you can install them anywhere you want. By default, the following paths are configured:

- **`cortex-module`** - `config('rinvex.composer.cortex-modules.path')` (default: `app/modules`) 
- **`cortex-extension`** - `config('rinvex.composer.cortex-extensions.path')` (default: `app/extensions`)

So if you're building a new Rinvex Cortex module, you have to add the appropriate composer package type in your package's `composer.json`, such as `"type": "cortex-module"` for modules, and `"type": "cortex-extension"` for extensions, and then run `composer install` or `composer update` at your application root directory, and **Rinvex Composer** Installer will detect the package type and install it to the appropriate directory.


## Resources

- [A Multi-Framework Composer Library Installer](https://github.com/composer/installers)
- [Setting up and using custom Composer installers](https://github.com/composer/composer/blob/master/doc/articles/custom-installers.md)


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](https://bit.ly/rinvex-slack)
- [Help on Email](mailto:help@rinvex.com)
- [Follow on Twitter](https://twitter.com/rinvex)


## Contributing & Protocols

Thank you for considering contributing to this project! The contribution guide can be found in [CONTRIBUTING.md](CONTRIBUTING.md).

Bug reports, feature requests, and pull requests are very welcome.

- [Versioning](CONTRIBUTING.md#versioning)
- [Pull Requests](CONTRIBUTING.md#pull-requests)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Feature Requests](CONTRIBUTING.md#feature-requests)
- [Git Flow](CONTRIBUTING.md#git-flow)


## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to [help@rinvex.com](help@rinvex.com). All security vulnerabilities will be promptly addressed.


## About Rinvex

Rinvex is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Alexandria, Egypt since June 2016. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That’s how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2016-2022 Rinvex LLC, Some rights reserved.
