<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Http\Requests;

use Everzel\NovaFileManager\Rules\DiskExistsRule;
use Everzel\NovaFileManager\Rules\ExistsInFilesystem;

/**
 * @property-read string $path
 */
class DeleteFolderRequest extends BaseRequest
{
    public function authorize(): bool
    {
        return $this->canDeleteFolder();
    }

    public function rules(): array
    {
        return [
            'disk' => ['sometimes', 'string', new DiskExistsRule()],
            'path' => ['sometimes', 'string', new ExistsInFilesystem($this)],
        ];
    }
}
