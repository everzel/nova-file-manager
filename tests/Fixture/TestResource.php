<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Tests\Fixture;

use Illuminate\Foundation\Auth\User;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;
use Everzel\NovaFileManager\FileManager;

class TestResource extends Resource
{
    public static $model = User::class;

    public function fields(NovaRequest $request): array
    {
        return [
            FileManager::make('Image'),
        ];
    }
}
