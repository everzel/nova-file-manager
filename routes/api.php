<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Everzel\NovaFileManager\Http\Controllers\DiskController;
use Everzel\NovaFileManager\Http\Controllers\FileController;
use Everzel\NovaFileManager\Http\Controllers\FolderController;
use Everzel\NovaFileManager\Http\Controllers\IndexController;

/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

Route::as('nova-file-manager.')->middleware('nova')->group(static function () {
    Route::prefix('disks')->as('disks.')->group(static function () {
        Route::get('/{page?}', [DiskController::class, 'available'])->name('available');
    });

    Route::get('/{page?}', IndexController::class)->name('data');

    Route::prefix('files')->as('files.')->group(function () {
        Route::post('upload/{page?}', [FileController::class, 'upload'])->name('upload');
        Route::post('rename/{page?}', [FileController::class, 'rename'])->name('rename');
        Route::post('delete/{page?}', [FileController::class, 'delete'])->name('delete');
        Route::post('unzip/{page?}', [FileController::class, 'unzip'])->name('unzip');
    });

    Route::prefix('folders')->as('folders.')->group(function () {
        Route::post('create/{page?}', [FolderController::class, 'create'])->name('create');
        Route::post('rename/{page?}', [FolderController::class, 'rename'])->name('rename');
        Route::post('delete/{page?}', [FolderController::class, 'delete'])->name('delete');
    });
});
