<?php

declare(strict_types=1);

use Laravel\Nova\Nova;
use Everzel\NovaFileManager\NovaFileManager;
use Everzel\NovaFileManager\Tests\DuskTestCase;
use Everzel\NovaFileManager\Tests\TestCase;
use Everzel\NovaFileManager\Tests\Traits\FileConcerns;
use Everzel\NovaFileManager\Tests\Traits\FolderConcerns;

uses(TestCase::class)->in('Feature');
uses(FolderConcerns::class)->in('Feature/Directory');
uses(FileConcerns::class)->in('Feature/File');
uses(DuskTestCase::class)->in('Browser');

uses()->group('integration')->in('Feature');
uses()->group('browser')->in('Browser');

uses()->beforeEach(function () {
    Nova::$tools = [
        NovaFileManager::make(),
    ];
})->in('Feature');
