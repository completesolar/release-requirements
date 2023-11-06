# Laravel release requirements auto launcher

**Complete PHPDocs, directly from the source**

This package generates requirements files that will be launched on laravel project deploy process within `artisan migrate` and `artisan dn:seed` commands, depends on requirement file stage.

It supports Laravel 9+ and PHP 8.1+

- [Installation](#installation)
- [Usage](#usage)
- [License](#license)

## Installation

Require this package with composer using the following command:

```bash
composer require completesolar/release-requirements
```

Publish migration table and config file:
```bash
php artisan vendor:publish --tag=release-requirements
```

Run database migrations:
```bash
php artisan migrate
```

This package makes use of [Laravels package auto-discovery mechanism](https://medium.com/@taylorotwell/package-auto-discovery-in-laravel-5-5-ea9e3ab20518).

## Usage

- `php artisan make:requirement {stage} {requirement_name}` - generate new requirement file for specified stage(before_migrate, after_migrate, before_seed, after_seed)
- `php artisan requirement:run {stage} {?requirement_name}` - run requirements for specified stage. Requirement name can be passed as a second param to run only that requirement.

```bash
php artisan vendor:publish --provider="Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider" --tag=config
```

You can change requirements files directory or disable this feature using the requirement.php config file.

## License

The Laravel Release Requirements Auto Launcher is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
