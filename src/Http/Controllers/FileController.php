<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Everzel\NovaFileManager\Contracts\Filesystem\Upload\Uploader;
use Everzel\NovaFileManager\Events\FileDeleted;
use Everzel\NovaFileManager\Events\FileDeleting;
use Everzel\NovaFileManager\Events\FileRenamed;
use Everzel\NovaFileManager\Events\FileRenaming;
use Everzel\NovaFileManager\Events\FileUnzipped;
use Everzel\NovaFileManager\Events\FileUnzipping;
use Everzel\NovaFileManager\Http\Requests\DeleteFileRequest;
use Everzel\NovaFileManager\Http\Requests\RenameFileRequest;
use Everzel\NovaFileManager\Http\Requests\UnzipFileRequest;
use Everzel\NovaFileManager\Http\Requests\UploadFileRequest;

class FileController extends Controller
{
    /**
     * Upload a file from the tool
     *
     * @param \Everzel\NovaFileManager\Http\Requests\UploadFileRequest $request
     * @param \Everzel\NovaFileManager\Contracts\Filesystem\Upload\Uploader $uploader
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(UploadFileRequest $request, Uploader $uploader): JsonResponse
    {
        return response()->json(
            $uploader->handle($request)
        );
    }

    /**
     * Rename a file
     *
     * @param \Everzel\NovaFileManager\Http\Requests\RenameFileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rename(RenameFileRequest $request): JsonResponse
    {
        $manager = $request->manager();

        event(new FileRenaming($manager->filesystem(), $manager->getDisk(), $request->from, $request->to));

        $result = $manager->rename($request->from, $request->to);

        if (!$result) {
            throw ValidationException::withMessages([
                'from' => [__('nova-file-manager::errors.file.rename')],
            ]);
        }

        event(new FileRenamed($manager->filesystem(), $manager->getDisk(), $request->from, $request->to));

        return response()->json([
            'message' => __('nova-file-manager::messages.file.rename'),
        ]);
    }

    /**
     * Delete a file
     *
     * @param \Everzel\NovaFileManager\Http\Requests\DeleteFileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteFileRequest $request): JsonResponse
    {
        $manager = $request->manager();

        foreach ($request->paths as $path) {
            event(new FileDeleting($manager->filesystem(), $manager->getDisk(), $path));

            $result = $manager->delete($path);

            if (!$result) {
                throw ValidationException::withMessages([
                    'paths' => [__('nova-file-manager::errors.file.delete')],
                ]);
            }

            event(new FileDeleted($manager->filesystem(), $manager->getDisk(), $path));
        }

        return response()->json([
            'message' => __('nova-file-manager::messages.file.delete'),
        ]);
    }

    /**
     * Unzip an archive
     *
     * @param \Everzel\NovaFileManager\Http\Requests\UnzipFileRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function unzip(UnzipFileRequest $request): JsonResponse
    {
        $manager = $request->manager();

        event(new FileUnzipping($manager->filesystem(), $manager->getDisk(), $request->path));

        $result = $manager->unzip($request->path);

        if (!$result) {
            throw ValidationException::withMessages([
                'path' => [__('nova-file-manager::errors.file.unzip')],
            ]);
        }

        event(new FileUnzipped($manager->filesystem(), $manager->getDisk(), $request->path));

        return response()->json([
            'message' => __('nova-file-manager::messages.file.unzip'),
        ]);
    }
}
