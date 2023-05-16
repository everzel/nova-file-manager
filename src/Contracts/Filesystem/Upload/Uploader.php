<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Contracts\Filesystem\Upload;

use Everzel\NovaFileManager\Http\Requests\UploadFileRequest;

interface Uploader
{
    public function handle(UploadFileRequest $request, string $index = 'file'): array;
}
