# Rinvex Composer

**Rinvex Composer** is an intuitive package that utilizes Composer Plugin API to support additional actions during installation, such as installing packages outside of the default vendor library and running custom scripts during install, update, and uninstall processes.

[![Packagist](https://img.shields.io/packagist/v/rinvex/composer.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/rinvex/composer)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/rinvex/composer.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/rinvex/composer/)
[![Code Climate](https://img.shields.io/codeclimate/github/rinvex/composer.svg?label=CodeClimate&style=flat-square)](https://codeclimate.com/github/rinvex/composer)
[![Travis](https://img.shields.io/travis/rinvex/composer.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/rinvex/composer)
[![SensioLabs Insight](https://img.shields.io/sensiolabs/i/97e0d74b-5f4c-4000-8a06-16a1331aaffb.svg?label=SensioLabs&style=flat-square)](https://insight.sensiolabs.com/projects/97e0d74b-5f4c-4000-8a06-16a1331aaffb)
[![StyleCI](https://styleci.io/repos/77618130/shield)](https://styleci.io/repos/77618130)
[![License](https://img.shields.io/packagist/l/rinvex/composer.svg?label=License&style=flat-square)](https://github.com/rinvex/composer/blob/develop/LICENSE)

**Rinvex Composer** also handles any module specific install/uninstall logic, so if your custom **cortext-module** contains for example migrations and/or seeds, it will be automatically executed upon composer installation.


## Installation

1. Install the package via composer:
    ```shell
    composer require rinvex/composer
    ```

2. **Optionally** you can publish config files by running the following commands:
    ```shell
    php artisan vendor:publish --tag="rinvex-composer-config"
    ```

3. Done!


## Usage

As it should be clear, the main purpose of this package is to modify composer's behaviour so packages of custom types could be installed in directories other than the default `vendor`.

### Supported Package Types

- cortex-module
- cortex-custom

> **Note:** Checkout Composer's [Custom Installers](https://github.com/composer/composer/blob/master/doc/articles/custom-installers.md)

### Basic Usage

All Rinvex Cortex modules are installed into the following paths accordingly:

- **`cortex-module`** - `app`
- **`cortex-custom`** - `custom/path`

So if you're building a new Rinvex Cortex module, you have to add the appropriate composer package type in your package's `composer.json`, such as `"type": "cortex-module"` for modules.

> **Note:** Checkout Rinvex [Module Package](https://github.com/rinvex/module) documentation for further details.

### Custom Paths

This is a powerful feature available for more flexibility and control over package installation paths. To use custom paths, your package's `composer.json` file must have the following attributes:
```json
"type": "cortex-custom",
"require": {
    "rinvex/composer": "^1.0.0"
},
"extra": {
    "path": "custom/path/"
}
```
Then you've to run `composer install` or `composer update` at your application root directory, and **Rinvex Composer** Installer will detect the custom package type and look for `extra.path`. If it finds it; the package will be installed to that custom directory.

### Overriding Custom Paths

It's nice to give packages the ability to set their own installation paths, but on some hosts where there's some restrictions it may be a problem; In such case you may have to take control and enforce these packages to be installed within certain directory.

You can override package-level paths at the application-level through `extra.paths` attribute in your application `composer.json` file:
```json
"require": {
    "vendor/package": "^1.0.0"
},
"extra": {
    "paths": {
        "vendor/package": "enforced/overriden/path/for/vendor/package/"
    }
}
```

Accordingly, this application-level path override will take precedence over any package-level custom paths.


## Resources

- [A Multi-Framework Composer Library Installer](https://github.com/composer/installers)
- [Setting up and using custom Composer installers](https://github.com/composer/composer/blob/master/doc/articles/custom-installers.md)


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](http://chat.rinvex.com)
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

(c) 2016-2017 Rinvex LLC, Some rights reserved.
