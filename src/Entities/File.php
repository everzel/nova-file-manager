<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Entities;

class File extends Entity
{
    public function meta(): array
    {
        return [
            'type' => 'file',
        ];
    }
}
