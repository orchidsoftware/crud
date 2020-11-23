<?php

namespace Orchid\Crud;

use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Orchid\Screen\Field;
use Orchid\Screen\TD;

abstract class Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = '';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = '';

    /**
     * Get the value that should be displayed to represent the resource.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->{static::$title};
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label(): string
    {
        return Str::plural(static::nameWithoutResource());
    }

    /**
     * Get the displayable icon of the resource.
     *
     * @return string
     */
    public static function icon(): string
    {
        return 'folder';
    }

    /**
     * Get the displayable sort of the resource.
     *
     * @return string
     */
    public static function sort(): string
    {
        return 2000;
    }

    /**
     * Get the columns displayed by the resource.
     *
     * @return TD[]
     */
    abstract public function columns(): array;

    /**
     * Get the fields displayed by the resource.
     *
     * @return Field[]
     */
    abstract public function fields(): array;

    /**
     * Get the URI key for the resource.
     *
     * @return string
     */
    public static function uriKey(): string
    {
        return Str::of(class_basename(static::class))->kebab()->plural();
    }

    /**
     * Get the permission key for the resource.
     *
     * @return string|null
     */
    public static function permission(): ?string
    {
        return null;
    }

    /**
     * The underlying model resource instance.
     *
     * @return Model
     */
    public function getModel(): Model
    {
        return app(static::$model);
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel(): string
    {
        return Str::of(static::nameWithoutResource())->snake(' ')->title()->singular();
    }

    /**
     * @return string
     */
    public static function nameWithoutResource(): string
    {
        return Str::of(class_basename(static::class))->replace('Resource', '');
    }

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

    /**
     * Get the text for the update resource button.
     *
     * @return string
     */
    public static function updateButtonLabel(): string
    {
        return __('Update :resource', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the text for the update resource toast.
     *
     * @return string
     */
    public static function updateToastMessage(): string
    {
        return __('The :resource was updated!', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the text for the delete resource button.
     *
     * @return string
     */
    public static function deleteButtonLabel(): string
    {
        return __('Delete :resource', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the text for the delete resource toast.
     *
     * @return string
     */
    public static function deleteToastMessage(): string
    {
        return __('The :resource was deleted!', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the text for the save resource button.
     *
     * @return string
     */
    public static function saveButtonLabel(): string
    {
        return __('Save :resource', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the text for the restore resource button.
     *
     * @return string
     */
    public static function restoreButtonLabel(): string
    {
        return __('Restore :resource', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the text for the restore resource toast.
     *
     * @return string
     */
    public static function restoreToastMessage(): string
    {
        return __('The :resource was restored!', ['resource' => static::singularLabel()]);
    }

    /**
     * Get the validation rules that apply to save/update.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @return array
     */
    public function filters(): array
    {
        return [];
    }

    /**
     * Get relationships that should be eager loaded when performing an index query.
     *
     * @return array
     */
    public function with(): array
    {
        return [];
    }

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

    /**
     * Determine if this resource uses soft deletes.
     *
     * @return bool
     */
    public static function softDeletes(): bool
    {
        return in_array(SoftDeletes::class, class_uses_recursive(static::$model), true);
    }

    /**
     * Action to restore a model
     *
     * @param Model $model
     */
    public function onRestore(Model $model)
    {
        $model->restore();
    }

    /**
     * Action to Force delete a model
     *
     * @param Model $model
     *
     * @throws Exception
     */
    public function onForceDelete(Model $model)
    {
        // Force deleting a single model instance...
        $model->forceDelete();
    }
}
