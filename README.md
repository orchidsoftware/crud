# Laravel Orchid CRUD

![Tests](https://github.com/orchidsoftware/crud/workflows/Tests/badge.svg)


## Introduction

Laravel Orchid has provided a unique experience for writing applications, but sometimes a simple CRUD needs to be done when it might seem overkill. Therefore, we have created a new package aimed at developers who want to quickly create a user interface for eloquent models with functions such as create, read, update, and delete.


You can describe the entire process using one file. And when you need more options, it's easy to switch to using the platform.
All fields, filters, and traits are compatible.

## Installation

You can install the package via composer:

```bash
composer require orchid/crud
```

## Defining Resources

Resources are stored are stored in the `app/Orchid/Resources` directory of your application.
You may generate a new resource using the `orchid:resource` Artisan command:

```bash
php artisan orchid:resource Post
```

The most basic and fundamental property of a resource is its `model` property. 
This property tells the generator which Eloquent model the resource corresponds to:

```php
/**
 * The model the resource corresponds to.
 *
 * @var string
 */
public static $model = 'App\Models\Post';
```

Freshly created Nova resources only contain an `ID` field definition. Don't worry, we'll add more fields to our resource soon.


## Registering Resources

By default, all resources within the `app/Orchid/Resources` directory will automatically be registered.
You are not required to manually register them.


## Usage

``` php
// ...
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Alexandr Chernyaev](https://github.com/tabuna)
- [All Contributors](../../contributors)

## License

Please see [License File](LICENSE.md) for more information.
