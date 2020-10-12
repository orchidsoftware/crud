# Laravel Orchid CRUD

![Tests](https://github.com/orchidsoftware/crud/workflows/Tests/badge.svg)
![Open Collective backers and sponsors](https://img.shields.io/opencollective/all/orchid)



## Introduction

<a href="https://orchid.software/" target="blank">Laravel Orchid</a> has provided a unique experience for writing applications, but sometimes a simple CRUD needs to be done when it might seem overkill. Therefore, we have created a new package aimed at developers who want to quickly create a user interface for eloquent models with functions such as create, read, update, and delete.


You can describe the entire process using one file. And when you need more options, it's easy to switch to using the platform.
All fields, filters, and traits are compatible.

## Installation

You can install the package using the Сomposer.
To do this, you need to install a new Composer repository in the `composer.json` file of your Laravel application.

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://github.com/orchidsoftware/crud.git"
    }
],
```

Next, add personal token to the config section of your `composer.json` file:

```json
"config": {
    "github-oauth": {
        "github.com": "XXXXXXXXXXXXXXXXXXXXXX"
    }
},
```

No token? Head to [settings page](https://github.com/settings/tokens/new?scopes=repo&description=Orchid+CRUD) to retrieve a token.

> If you don't want to store the token in your composer file, you can skip this step. When installing, Composer will ask for it on its own and save it to ".composer/auth.json". Or you can specify it yourself by running: `composer config -g github-oauth.github.com XXXXXXXXXXXXXXXXXXXXXXX`
 
Next, add `orchid/crud` to the require section of your `composer.json` file:

```json
"require": {
    "orchid/crud": "*"
},
```

After your `composer.json` file has been updated, run the composer update command in your console terminal:

```bash
composer update
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
use App\Models\Post;

/**
 * The model the resource corresponds to.
 *
 * @var string
 */
public static $model = Post::class;
```

Freshly created resources contain nothing. Don't worry, we'll add more fields to our resource soon.

## Registering Resources

By default, all resources within the `app/Orchid/Resources` directory will automatically be registered.
You are not required to manually register them.


## Permissions Resources

For each registered resource, a new permission is created. It is necessary to give the right to manage it to the user.
To click on the profile in the left column and go to the system page, and then to the page with users, 
where you can issue them a mandate or assign a role. After that, they will be displayed in the left menu.

## Defining Fields

Each resource contains a `fields` method. This method returns an array of fields, which generally extend the `Orchid\Screen\Field` class. 

To add a field to a resource, we can simply add it to the resource's `fields` method. Typically, fields may be created using their static `make` method. This method accepts several arguments; however, you usually only need to pass the name of the field.


```php
use Orchid\Screen\Fields\Input;

/**
 * Get the fields displayed by the resource.
 *
 * @return array
 */
public function fields(): array
{
    return [
        Input::make('title')
            ->title('Title')
            ->placeholder('Enter title here'),
    ];
}
```
In the package to generate CRUD, you can use the fields Orchid platform. Review [all available fields on the documentation site](https://orchid.software/en/docs/field/).


## Defining Сolumns

Each resource contains a `сolumns` method. To add a column to a resource, we can simply add it to the resource's `column` method. Typically, columns may be created using their static `make` method. 

```php
use Orchid\Screen\TD;

/**
 * Get the columns displayed by the resource.
 *
 * @return TD[]
 */
public function columns(): array
{
    return [
        TD::set('id'),
        TD::set('title'),
    ];
}
```
The CRUD generation package is entirely based on the table layer. You can [read more about this on the documentation page](https://orchid.software/en/docs/layouts/table/).

## Defining Rules

TODO:
``` php
// ...
```

## Defining Filters

TODO:
``` php
// ...
```

## Eager Loading

If you routinely need to access a resource's relationships within your fields, it may be a good idea to add the relationship to the `with` property of your resource. This property instructs Nova to always eager load the listed relationships when retrieving the resource.

For example, if you access a `Post` resource's `user` relationship within the `Post` resource's `subtitle` method, you should add the `user` relationship to the `Post` resource's `with` property:

```php
 /**
 * Get relationships that should be eager loaded when performing an index query.
 *
 * @return array
 */
public function with(): array
{
    return ['user'];
}
```

## Resource Events

TODO:
``` php
// ...
```

## Localization

TODO:
``` php
// ...
```


## Backers

Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/orchid#backer)]

<a href="https://opencollective.com/colly#backers" target="_blank"><img src="https://opencollective.com/orchid/backers.svg?width=838"></a>

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## License

Please see [License File](LICENSE) for more information.
