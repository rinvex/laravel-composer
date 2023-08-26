# Rinvex Composer Change Log

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](CONTRIBUTING.md).


## [v7.6.1] - 2023-08-27
- Revert "Use realpath to return canonicalized absolute pathnames"

## [v7.6.0] - 2023-08-26
- Update readme documentation
- Add support for cortex-theme new package types
- Use realpath to return canonicalized absolute pathnames
- Remove useless code

## [v7.5.11] - 2023-08-02
- Fix isAlwaysActive parameter issue

## [v7.5.10] - 2023-07-03
- Update composer dependencies

## [v7.5.9] - 2023-06-30
- Fix absolute root path in case modules/extensions directories doesn't exist

## [v7.5.8] - 2023-06-30
- Use canonicalized absolute pathnames for resources
- Return canonicalized absolute pathname

## [v7.5.7] - 2023-06-30
- Drop using Laravel application instance
  - It's very early to use Laravel here and results in weird and inconsistent behavior.
  - We can still use configurable options via environment variables, which is working fine.
  - This package is called when composer is running, is before any Laravel features is loaded.

## [v7.5.6] - 2023-06-30
- Fix Application instantiation app root directory path

## [v7.5.5] - 2023-06-29
- Fix application object instantiation and pass current working directory

## [v7.5.4] - 2023-06-29
- Reverse "Add application macros getCachedModulesPath & getCachedExtensionsPath"

## [v7.5.3] - 2023-06-29
- Fix app instance used very early

## [v7.5.2] - 2023-06-29
- Fix app instance used very early

## [v7.5.1] - 2023-06-29
- Fix app instance used very early

## [v7.5.0] - 2023-06-29
- Add application macros getCachedModulesPath & getCachedExtensionsPath
- Tweak and improve custom composer plugin/installer
- Simplify namespaces

## [v7.4.0] - 2023-06-25
- Refactor to support dynamic custom package types and drop CustomPlugin

## [v7.3.1] - 2023-06-24
- Register two different composer installer instances for modules & extensions

## [v7.3.0] - 2023-06-24
- Refactor to support modules & extensions, and simplify event listeners

## [v7.2.2] - 2023-06-23
- Check if module composer extra has ['cortex-module']['extends'] key or fail gracefully

## [v7.2.1] - 2023-06-13
- Fix wrong variable name

## [v7.2.0] - 2023-06-10
- Rename isCore to isAlwaysActive
- Add module extensions support to composer module installer

## [v7.1.0] - 2023-05-02
- 72b7984: Add support for Laravel v11, and drop support for Laravel v9
- 8b682d5: Upgrade composer/composer to v2.5 from v2.0
- 43cc0c9: Update phpunit to v10.1 from v9.5

## [v7.0.0] - 2023-01-09
- Tweak artisan commands registration
- Drop PHP v8.0 support and update composer dependencies
- Utilize PHP 8.1 attributes feature for artisan commands

## [v6.1.0] - 2022-02-14
- Update composer dependencies to Laravel v9

## [v6.0.1] - 2021-12-08
- Print module name on upgrade/downgrade and fix upgrade link

## [v6.0.0] - 2021-08-22
- Drop PHP v7 support, and upgrade rinvex package dependencies to next major version
- Update composer dependencies

## [v5.0.3] - 2020-12-29
- Rename core_modules to always_active
- Add get method to Manifest class for retrieving module attributes

## [v5.0.2] - 2020-12-27
- Apply fixes from StyleCI
- Tweak and optimize module and custom composer installer
- Enable StyleCI risky mode

## [v5.0.1] - 2020-12-25
- Add support for PHP v8

## [v5.0.0] - 2020-12-22
- Upgrade to Laravel v8 & composer v2

## [v4.2.2] - 2020-08-29
- Tweak module loading logic to fix existing config override

## [v4.2.1] - 2020-08-29
- Tweak module loading logic to fix existing config override

## [v4.2.0] - 2020-08-04
- Add ability to autoload/unload installed/uninstalled modules

## [v4.1.0] - 2020-06-15
- Drop PHP 7.2 & 7.3 support from travis

## [v4.0.5] - 2020-04-12
- Fix ServiceProvider registerCommands method compatibility

## [v4.0.4] - 2020-04-09
- Tweak artisan command registration
- Refactor publish command and allow multiple resource values

## [v4.0.3] - 2020-04-04
- Fix namespace issue

## [v4.0.2] - 2020-04-04
- Enforce consistent artisan command tag namespacing
- Enforce consistent package namespace
- Drop laravel/helpers usage as it's no longer used

## [v4.0.1] - 2020-03-20
- Convert into bigInteger database fields
- Add shortcut -f (force) for artisan publish commands
- Fix migrations path

## [v4.0.0] - 2020-03-15
- Upgrade to Laravel v7.1.x & PHP v7.4.x

## [v3.0.1] - 2020-03-13
- Tweak TravisCI config
- Drop using global helpers
- Update StyleCI config

## [v3.0.0] - 2019-09-23
- Upgrade to Laravel v6 and update dependencies

## [v2.1.1] - 2019-06-03
- Enforce latest composer package versions

## [v2.1.0] - 2019-06-02
- Update composer deps
- Drop PHP 7.1 travis test

## [v2.0.0] - 2019-03-03
- Rename environment variable QUEUE_DRIVER to QUEUE_CONNECTION
- Require PHP 7.2 & Laravel 5.8
- Apply PHPUnit 8 updates
- Tweak and simplify FormRequest validations

## [v1.0.2] - 2018-12-22
- Add missing console/command namespace

## [v1.0.1] - 2018-12-22
- Add publish command
- Update composer dependencies
- Add PHP 7.3 support to travis

## [v1.0.0] - 2018-10-01
- Enforce Consistency
- Support Laravel 5.7+
- Rename package to rinvex/laravel-composer

## [v0.0.2] - 2018-09-22
- Update travis php versions
- Drop StyleCI multi-language support (paid feature now!)
- Update composer dependencies
- Prepare and tweak testing configuration
- Highlight variables in strings explicitly
- Update StyleCI options
- Update PHPUnit options

## v0.0.1 - 2018-02-18
- Tag first release

[v7.6.1]: https://github.com/rinvex/laravel-composer/compare/v7.6.0...v7.6.1
[v7.6.0]: https://github.com/rinvex/laravel-composer/compare/v7.5.11...v7.6.0
[v7.5.11]: https://github.com/rinvex/laravel-composer/compare/v7.5.10...v7.5.11
[v7.5.10]: https://github.com/rinvex/laravel-composer/compare/v7.5.9...v7.5.10
[v7.5.9]: https://github.com/rinvex/laravel-composer/compare/v7.5.8...v7.5.9
[v7.5.8]: https://github.com/rinvex/laravel-composer/compare/v7.5.7...v7.5.8
[v7.5.7]: https://github.com/rinvex/laravel-composer/compare/v7.5.6...v7.5.7
[v7.5.6]: https://github.com/rinvex/laravel-composer/compare/v7.5.5...v7.5.6
[v7.5.5]: https://github.com/rinvex/laravel-composer/compare/v7.5.4...v7.5.5
[v7.5.4]: https://github.com/rinvex/laravel-composer/compare/v7.5.3...v7.5.4
[v7.5.3]: https://github.com/rinvex/laravel-composer/compare/v7.5.2...v7.5.3
[v7.5.2]: https://github.com/rinvex/laravel-composer/compare/v7.5.1...v7.5.2
[v7.5.1]: https://github.com/rinvex/laravel-composer/compare/v7.5.0...v7.5.1
[v7.5.0]: https://github.com/rinvex/laravel-composer/compare/v7.4.0...v7.5.0
[v7.4.0]: https://github.com/rinvex/laravel-composer/compare/v7.3.1...v7.4.0
[v7.3.1]: https://github.com/rinvex/laravel-composer/compare/v7.3.0...v7.3.1
[v7.3.0]: https://github.com/rinvex/laravel-composer/compare/v7.2.2...v7.3.0
[v7.2.2]: https://github.com/rinvex/laravel-composer/compare/v7.2.1...v7.2.2
[v7.2.1]: https://github.com/rinvex/laravel-composer/compare/v7.2.0...v7.2.1
[v7.2.0]: https://github.com/rinvex/laravel-composer/compare/v7.1.0...v7.2.0
[v7.1.0]: https://github.com/rinvex/laravel-composer/compare/v7.0.0...v7.1.0
[v7.0.0]: https://github.com/rinvex/laravel-composer/compare/v6.1.0...v7.0.0
[v6.1.0]: https://github.com/rinvex/laravel-composer/compare/v6.0.1...v6.1.0
[v6.0.1]: https://github.com/rinvex/laravel-composer/compare/v6.0.0...v6.0.1
[v6.0.0]: https://github.com/rinvex/laravel-composer/compare/v5.0.3...v6.0.0
[v5.0.3]: https://github.com/rinvex/laravel-composer/compare/v5.0.2...v5.0.3
[v5.0.2]: https://github.com/rinvex/laravel-composer/compare/v5.0.1...v5.0.2
[v5.0.1]: https://github.com/rinvex/laravel-composer/compare/v5.0.0...v5.0.1
[v5.0.0]: https://github.com/rinvex/laravel-composer/compare/v4.2.2...v5.0.0
[v4.2.2]: https://github.com/rinvex/laravel-composer/compare/v4.2.1...v4.2.2
[v4.2.1]: https://github.com/rinvex/laravel-composer/compare/v4.2.0...v4.2.1
[v4.2.0]: https://github.com/rinvex/laravel-composer/compare/v4.1.0...v4.2.0
[v4.1.0]: https://github.com/rinvex/laravel-composer/compare/v4.0.5...v4.1.0
[v4.0.5]: https://github.com/rinvex/laravel-composer/compare/v4.0.4...v4.0.5
[v4.0.4]: https://github.com/rinvex/laravel-composer/compare/v4.0.3...v4.0.4
[v4.0.3]: https://github.com/rinvex/laravel-composer/compare/v4.0.2...v4.0.3
[v4.0.2]: https://github.com/rinvex/laravel-composer/compare/v4.0.1...v4.0.2
[v4.0.1]: https://github.com/rinvex/laravel-composer/compare/v4.0.0...v4.0.1
[v4.0.0]: https://github.com/rinvex/laravel-composer/compare/v3.0.1...v4.0.0
[v3.0.1]: https://github.com/rinvex/laravel-composer/compare/v3.0.0...v3.0.1
[v3.0.0]: https://github.com/rinvex/laravel-composer/compare/v2.1.1...v3.0.0
[v2.1.1]: https://github.com/rinvex/laravel-composer/compare/v2.1.0...v2.1.1
[v2.1.0]: https://github.com/rinvex/laravel-composer/compare/v2.0.0...v2.1.0
[v2.0.0]: https://github.com/rinvex/laravel-composer/compare/v1.0.2...v2.0.0
[v1.0.2]: https://github.com/rinvex/laravel-composer/compare/v1.0.1...v1.0.2
[v1.0.1]: https://github.com/rinvex/laravel-composer/compare/v1.0.0...v1.0.1
[v1.0.0]: https://github.com/rinvex/laravel-composer/compare/v0.0.2...v1.0.0
[v0.0.2]: https://github.com/rinvex/laravel-composer/compare/v0.0.1...v0.0.2
