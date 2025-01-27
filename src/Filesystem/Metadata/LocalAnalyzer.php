<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Filesystem\Metadata;

use Everzel\NovaFileManager\Filesystem\Support\GetID3;

class LocalAnalyzer extends Analyzer
{
    protected function rawAnalyze(string $path): array
    {
        return (new GetID3())->analyze($this->disk->path($path));
    }
}
