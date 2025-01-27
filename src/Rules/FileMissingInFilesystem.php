<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Rules;

use Illuminate\Contracts\Validation\Rule;
use Everzel\NovaFileManager\Http\Requests\UploadFileRequest;

class FileMissingInFilesystem implements Rule
{
    public function __construct(public UploadFileRequest $request)
    {
    }

    /**
     * @param $attribute
     * @param  \Illuminate\Http\UploadedFile  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return $this->request
            ->manager()
            ->filesystem()
            ->missing($this->request->filePath());
    }

    public function message(): string
    {
        return __('nova-file-manager::validation.path.exists', ['path' => $this->request->filePath()]);
    }
}
