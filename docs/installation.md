# Installation & Setup

## Installing the package
To get started, you will need to install the following dependencies :

```bash
composer require everzel/nova-file-manager
```

🎉 That's it, you're ready to go!

## Configuration
You may publish the package's configuration by running the following command :

```bash
php artisan vendor:publish --tag="nova-file-manager-config"
```

::: tip NOTE
You can find details about the configuration options in the [configuration file section](/configuration).
:::

## Casting your model attributes
To use the package, you will need to cast your model attributes to the `Everzel\NovaFileManager\Casts\Asset` for a single value or `Everzel\NovaFileManager\Casts\AssetCollection` for multiple values.

```php
<?php

namespace App\Models;

use Everzel\NovaFileManager\Casts\AssetCollection;

class User extends Authenticatable
{
    protected $fillable = [
        'pictures',
    ];

    protected $casts = [
        'pictures' => AssetCollection::class,
    ];
}
```
