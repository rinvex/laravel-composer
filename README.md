# Rinvex Composer

**Rinvex Composer** is an intuitive package that leverages the Composer Plugin API, offering enhanced installation features, that allow packages to be installed outside the standard vendor directory and executing custom scripts during the install, update, and uninstall phases.

[![Packagist](https://img.shields.io/packagist/v/rinvex/laravel-composer.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/laravel-composer)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/laravel-composer.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/laravel-composer/)
[![Travis](https://img.shields.io/travis/rinvex/laravel-composer.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/laravel-composer)
[![StyleCI](https://styleci.io/repos/77618130/shield)](https://styleci.io/repos/77618130)
[![License](https://img.shields.io/packagist/l/rinvex/laravel-composer.svg?label=License&style=flat-square)](https://github.com/rinvex/laravel-composer/blob/develop/LICENSE)


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

The main purpose of this package is to adjust Composer's default behavior, allowing custom packages to install outside the `vendor` directory. It uses the Composer Plugin API and has built-in support for Rinvex Cortex modules, extensions, and themes.

### Supported Package Types

- cortex-theme
- cortex-module
- cortex-extension

> **Notes:**
> - Checkout Composer's [Custom Installers](https://github.com/composer/composer/blob/master/doc/articles/custom-installers.md)
> - You can add more custom package types via the config file at `config/rinvex.composer.php`. This file provides examples of custom package types. To understand how it works, consider examining the [composer plugin](src/Models/Plugin.php) logic.

### Basic Usage

Rinvex Cortex modules, extensions and themes are installed to customizable paths, allowing placement wherever you prefer. The default configurations use these paths:

- **`cortex-theme`** - `config('rinvex.composer.cortex-theme.path')` (default: `app/themes`) 
- **`cortex-module`** - `config('rinvex.composer.cortex-module.path')` (default: `app/modules`) 
- **`cortex-extension`** - `config('rinvex.composer.cortex-extension.path')` (default: `app/extensions`)

When creating a new Rinvex Cortex module, extension, or theme, specify the correct composer package type in your package's `composer.json`. Use `"type": "cortex-module"` for modules, `"type": "cortex-extension"` for extensions, and `"type": "cortex-theme"` for themes.

Afterward, execute `composer install` or `composer update` in your application's root directory. The Rinvex Composer Installer will identify the package type and place it in the correct directory.


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
