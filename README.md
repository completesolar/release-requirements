# Laravel release requirements auto launcher

**Complete PHPDocs, directly from the source**

This package generates requirements files that will be launched on laravel project deploy process within `artisan migrate` and `artisan db:seed` commands, depends on requirement file stage.

It supports Laravel 9+ and PHP 8.1+

- [Installation](#installation)
- [Usage](#usage)
- [Example](#example)
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

You can enable/disable auto-launcher for migration and seed command using config file conf/requirement.php:
```bash
'path' => base_path(env('REQUIREMENTS_PATH', 'requirements')),
'enabled' => env('REQUIREMENTS_ENABLED', true),
```

## Example
For example, we have a CI deployment process that will run the php `artisanmigrate` and `artisan db:seed` commands one after the other. We have a script that only needs to be run once before the artisan db:seed command, but that means we'll have to change the deployment process this time.
To solve this problem, we can create a new requirement:

- `php artisan make:requirement before_seed some_requirement_name` - by default, all new commands will be stored in the `project_path/requirements/` folder.
- Place our script in this new file.

That's all. The script will be automatically run on `artisan db:seed` before the seed stage.
Once deployed, the new files can be safely deleted.

## License

The Laravel Release Requirements Auto Launcher is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
