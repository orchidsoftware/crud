# Laravel Orchid CRUD

![Tests](https://github.com/orchidsoftware/crud/workflows/Tests/badge.svg)
![Open Collective backers and sponsors](https://img.shields.io/opencollective/all/orchid)


## Introduction

<a href="https://orchid.software/" target="blank">Laravel Orchid</a> has provided a unique experience for writing applications. Still, sometimes a simple CRUD needs to be done when it might seem overkill. Therefore, we have created a new package aimed at developers who want to quickly create a user interface for eloquent models with functions such as creating, reading, updating, and deleting.


You can describe the entire process using one file. And when you need more options, it's easy to switch to using the platform.
All fields, filters, and traits are compatible.

## Installation

You can install the package using the Сomposer. Run this at the command line:

```php
$ composer require orchid/crud
```

This will update `composer.json` and install the package into the `vendor/` directory.

## Defining Resources

Resources are stored in the `app/Orchid/Resources` directory of your application.
You may generate a new resource using the `orchid:resource` Artisan command:

```bash
php artisan orchid:resource PostResource
```

The most fundamental property of a resource is its `model` property. 
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

These classes are completely static. They don't have any state at all due to their declarative nature. 
They only tell what to do. They don't hold any data. So if you add custom methods, make sure they're static.


Freshly created resources contain nothing. Don't worry. We'll add more fields to our resource soon.

## Expanding of Model

Many features of the Orchid platform relies on model customization. You can add or remove traits depending on your goals. But we will assume that you have set these for your model:

```php
use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Post extends Model
{
    use AsSource, Filterable, Attachable;
}
````


## Registering Resources

All resources within the `app/Orchid/Resources` directory will automatically be registered by default.
You are not required to register them manually.


## Defining Fields

Each resource contains a `fields` method. This method returns an array of fields, which generally extend the `Orchid\Screen\Field` class. To add a field to a resource, we can add it to the resource's `fields` method. Typically, fields may be created using their static `make` method. This method accepts several arguments; however, you usually only need to pass the field's name.


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

Each resource contains a `сolumns` method. To add a column to a resource, we can add it to the resource's `column` method. Typically, columns may be created using their static `make` method. 

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
        TD::make('id'),
        TD::make('title'),
    ];
}
```
The CRUD generation package is entirely based on the table layer. You can [read more about this on the documentation page](https://orchid.software/en/docs/table/).

## Defining Rules

Each resource contains a `rules` method. When submitting a create or update form, the data can be validated, which is described in the `rules` method:

``` php
/**
 * Get the validation rules that apply to save/update.
 *
 * @return array
 */
public function rules(Model $model): array
{
    return [
        'slug' => [
            'required',
            Rule::unique(self::$model, 'slug')->ignore($model),
        ],
    ];
}
```

You can learn more on the Laravel [validation page](https://laravel.com/docs/validation#available-validation-rules).


## Defining Filters

Each resource contains a `filters` method. Which expects you to return a list of class names that should be rendered and, if necessary, swapped out for the viewed model.

``` php
/**
 * Get the filters available for the resource.
 *
 * @return array
 */
public function filters(): array
{
    return [];
}
```

To create a new filter, there is a command:

```bash
php artisan orchid:filter QueryFilter
```

This will create a class filter in the `app/Http/Filters` folder. To use filters in your own resource, you need:

```php
public function filters(): array
{
    return [
        QueryFilter::class
    ];
}
```


We already offer some prepared filters:

- `Orchid\Crud\Filters\DefaultSorted` - Setting default column sorting
- `Orchid\Crud\Filters\WithTrashed` - To display deleted records


```php
public function filters(): array
{
    return [
        new DefaultSorted('id', 'desc'),
    ];
}
```

You can learn more on the Orchid [filtration page](https://orchid.software/en/docs/filters/#eloquent-filter).

## Eager Loading

Suppose you routinely need to access a resource's relationships within your fields. In that case, it may be a good idea to add the relationship to the `with` property of your resource. This property instructs to always eager to load the listed relationships when retrieving the resource.

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

Each resource has two methods that do the processing, `onSave` and `onDelete`. Each of them is launched when the event is executed, and you can change or supplement the logic:

``` php
/**
 * Action to create and update the model
 *
 * @param ResourceRequest $request
 * @param Model           $model
 */
public function onSave(ResourceRequest $request, Model $model)
{
    $model->forceFill($request->all())->save();
}

/**
 * Action to delete a model
 *
 * @param Model $model
 *
 * @throws Exception
 */
public function onDelete(Model $model)
{
    $model->delete();
}
```


## Permissions Resources

Each resource contains a `permission` method, which should return the string key that the user needs to access this resource. By default, all resources are available to every user.

```php
/**
 * Get the permission key for the resource.
 *
 * @return string|null
 */
public static function permission(): ?string
{
    return null;
}
```

For each registered resource in which the method returns a non-null value, a new permission is created. 

```php
/**
 * Get the permission key for the resource.
 *
 * @return string|null
 */
public static function permission(): ?string
{
    return 'private-post-resource';
}
```

It is necessary to give the right to manage it to the user.
To click on the profile in the left column, go to the system page, and then to the page with users, 
you can issue them a mandate or assign a role. After that, they will be displayed in the left menu.

## Policies

To limit which users may view, create, update, or delete resources leverages Laravel's [authorization policies](https://laravel.com/docs/authorization#creating-policies). Policies are simple PHP classes that organize authorization logic for a particular model or resource. For example, if your application is a blog, you may have a `Post` model and a corresponding `PostPolicy` within your application.

Typically, these policies will be registered in your application's `AuthServiceProvider`. If CRUD detects a policy has been registered for the model, it will automatically check that policy's relevant authorization methods before performing their respective actions, such as:

- viewAny
- create
- update
- delete
- restore
- forceDelete

No additional configuration is required! So, for example, to determine which users are allowed to update a `Post` model, you need to define an `update` method on the model's corresponding policy class:

```php
namespace App\Policies;

use App\Models\User;
use App\Models\Post;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the post.
     *
     * @param  User  $user
     * @param  Post  $post
     * @return mixed
     */
    public function update(User $user, Post $post)
    {
        return true;
    }
}
```


> If a policy exists but is missing a particular action method, the user will not be allowed to perform that action. So, if you have defined a policy, don't forget to define all of its relevant authorization methods.


If you don't want the policy to affect CRUD generation users, you may wish to authorize all actions within a given policy. To accomplish this, define a `before` method on the policy. Before any other policy methods, the before method will be executed, allowing you to authorize the action before the intended policy method is actually called.

```php
namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PostPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->hasAccess('private-post-resource')) {
            return true;
        }
    }
}
```


## Preventing Conflicts (Traffic Cop)

If this option is active, the model will be checked for the last change if it was updated after the edit form was opened. A validation error will be thrown. Feature Traffic Cop is disabled by default.

```php
/**
 * Indicates whether should check for modifications between viewing and updating a resource.
 *
 * @return  bool
*/
public static function trafficCop(): bool
{
    return false;
}
```


## Localization

Resource names may be localized by overriding the `label` and `singularLabel` methods on the resource class:

``` php
/**
 * Get the displayable label of the resource.
 *
 * @return string
 */
public static function label()
{
    return __('Posts');
}

/**
 * Get the displayable singular label of the resource.
 *
 * @return string
 */
public static function singularLabel()
{
    return __('Post');
}
```

Action buttons and notifications can also be translated, for example:

```php
/**
 * Get the text for the create resource button.
 *
 * @return string|null
 */
public static function createButtonLabel(): string
{
    return __('Create :resource', ['resource' => static::singularLabel()]);
}

/**
 * Get the text for the create resource toast.
 *
 * @return string
 */
public static function createToastMessage(): string
{
    return __('The :resource was created!', ['resource' => static::singularLabel()]);
}
```

You can learn more on the [Laravel localization page](https://laravel.com/docs/localization).


## Backers

Thank you to all our backers! 🙏 [[Become a backer](https://opencollective.com/orchid#backer)]

<a href="https://opencollective.com/orchid#backers" target="_blank"><img src="https://opencollective.com/orchid/backers.svg?width=838"></a>

## Roadmap

This roadmap isn't meant to cover everything we're going to work on. We will continue to invest in making CRUD easier to use and easier to manage.
We want to do:
- [x] Add setting for displaying and restoring deleted models with `Illuminate\Database\Eloquent\SoftDeletes`;
- [ ] Add the ability to perform actions for multiple models;

It is an active community, so expect more contributions that will complement it with all sorts of other improvements to help improve production.


## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## License

Please see [License File](LICENSE) for more information.
