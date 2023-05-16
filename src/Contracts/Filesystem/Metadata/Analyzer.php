<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Contracts\Filesystem\Metadata;

interface Analyzer
{
    public function analyze(string $path): array|object;
}
