<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Http\Requests;

use Everzel\NovaFileManager\Rules\DiskExistsRule;
use Everzel\NovaFileManager\Rules\MissingInFilesystem;

/**
 * @property-read string|null $disk
 * @property-read string $path
 */
class CreateFolderRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->canCreateFolder();
    }

    public function rules(): array
    {
        return [
            'disk' => ['sometimes', 'string', new DiskExistsRule()],
            'path' => ['required', 'string', 'min:1', new MissingInFilesystem($this)],
        ];
    }
}
