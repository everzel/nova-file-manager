<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Filesystem\Metadata;

use Illuminate\Contracts\Filesystem\Filesystem;
use Everzel\NovaFileManager\Contracts\Filesystem\Metadata\Analyzer;

class Factory
{
    public static function build(Filesystem $fileSystem): ?Analyzer
    {
        $driver = data_get($fileSystem->getConfig(), 'driver');

        return match ($driver) {
            'local' => new LocalAnalyzer($fileSystem),
            's3' => new S3Analyzer($fileSystem),
            'ftp' => new FTPAnalyzer($fileSystem),
            default => null,
        };
    }
}
