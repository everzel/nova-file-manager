# Using the field

## Basic usage

You can start using the field by adding a `FileManager` field to your Nova resource :

```php
// app/Nova/Project.php

use Everzel\NovaFileManager\FileManager;

class Project extends Resource
{
    // ...

    public function fields(NovaRequest $request): array
    {
        return [
            // ... any other fields
            FileManager::make(__('Attachments'), 'attachments'),
        ];
    }
}
```

🎉 You have now successfully added a File Manager field to your resource.

<img src="./images/field.png" alt="field"/>

## Multiple selection

When using the `FileManager` field on your Nova resource, you can tell the tool to allow multiple selection for your
attribute.

By default, the tool will only allow single selection.

You can allow multiple selection by using the `multiple` method to the field. You may limit the number of selected field
by using the `limit` method.

```php
// app/Nova/Project.php

use Everzel\NovaFileManager\FileManager;

class Project extends Resource
{
    // ...

    public function fields(NovaRequest $request): array
    {
        return [
            // ... any other fields
            FileManager::make(__('Attachments'), 'attachments')
                ->multiple()
                ->limit(10),
        ];
    }
}
```


## Validation

When using the field, you can specify the number of files that can be set a value for your resource's attribute.

For that, you can specifically use the following custom rule :

```php
// app/Nova/Project.php

use Everzel\NovaFileManager\FileManager;
use Everzel\NovaFileManager\Rules\FileLimit;

class Project extends Resource
{
    // ...

    public function fields(NovaRequest $request): array
    {
        return [
            // ... any other fields
            FileManager::make(__('Attachments'), 'attachments')
                ->rules(new FileLimit(min: 3, max: 10))
                ->multiple()
                ->limit(10),
        ];
    }
}
```
::: tip NOTE
You need to set up your field with `multiple` if you plan on having a minimum value greater than one, and if
you expect your field to have more than one file.
:::

## Custom URL resolver

When using a multi-disk setup, the disk is saved alongside the path of your asset, however, if these two files come from
different filesystems, you may want to generate an URL with your own custom business logic.

For instance, having a `User` resource, to which you have references for `pictures` and the selection was as follows :

- my-picture.jpg (from the `public` disk)
- avatar.png (from the `s3`disk)

You may then use the `resolveUrlUsing` method to customize how the file URL is generated.

```php
// app/Nova/User.php

use Everzel\NovaFileManager\FileManager;
use Everzel\NovaFileManager\Rules\FileLimit;

class User extends Resource
{
    // ...

    public function fields(NovaRequest $request): array
    {
        return [
            // ... any other fields
            FileManager::make(__('Pictures'), 'pictures')
                ->resolveUrlUsing(function (NovaRequest $request, string $path, string $disk, Filesystem $filesystem) {
                    if ($disk === 's3') {
                        return $filesystem->temporaryUrl($path, now()->addMinutes(5));
                    }

                    return $filesystem->url($path);
                })
                ->limit(3),
        ];
    }
}
```

## Third-party compatibility

You may want to use the field inside a custom field or a tool (e.g [Nova Settings](https://github.com/outl1ne/nova-settings) or [Laravel Nova Flexible Content](https://github.com/whitecube/nova-flexible-content)). Some extra configuration is needed to be able to resolve the field during api calls.

You may register a wrapper which is callback to configure the field (filesystem, permission, etc.) :

```php
// app/Providers/NovaServiceProvider.php
<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use Everzel\NovaFileManager\Casts\Asset;
use Everzel\NovaFileManager\Casts\AssetCollection;
use Everzel\NovaFileManager\FileManager;
use Everzel\NovaFileManager\NovaFileManager;
use Outl1ne\NovaSettings\NovaSettings;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    public function boot()
    {
        parent::boot();

        NovaSettings::addSettingsFields(function () {
            return [
                Text::make('Some setting', 'some_setting'),
                FileManager::make('An image', 'image')
                    ->wrapper('my_wrapper'), // indicate which wrapper to use
            ];
        }, [
            'image' => AssetCollection::class, // do not forget to cast
        ]);
    }

    // ...

    public function register()
    {
        FileManager::registerWrapper('my_wrapper', function (FileManager $field) {
            // configure the field as you used to
            return $field
                ->multiple()
                // ...
                ->filesystem(fn() => 'public');
        });
    }
    
    public function tools()
    {
        return [
            NovaFileManager::make(),
            NovaSettings::make(),
        ];
    }
}
```
