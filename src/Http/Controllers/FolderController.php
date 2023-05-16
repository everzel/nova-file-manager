<?php

declare(strict_types=1);

namespace Everzel\NovaFileManager\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Everzel\NovaFileManager\Events\FolderCreated;
use Everzel\NovaFileManager\Events\FolderCreating;
use Everzel\NovaFileManager\Events\FolderDeleted;
use Everzel\NovaFileManager\Events\FolderDeleting;
use Everzel\NovaFileManager\Events\FolderRenamed;
use Everzel\NovaFileManager\Events\FolderRenaming;
use Everzel\NovaFileManager\Http\Requests\CreateFolderRequest;
use Everzel\NovaFileManager\Http\Requests\DeleteFolderRequest;
use Everzel\NovaFileManager\Http\Requests\RenameFolderRequest;

class FolderController extends Controller
{
    /**
     * Create a new folder
     *
     * @param  \Everzel\NovaFileManager\Http\Requests\CreateFolderRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateFolderRequest $request): JsonResponse
    {
        $path = trim($request->path);

        event(new FolderCreating($request->manager()->filesystem(), $request->manager()->getDisk(), $path));

        $result = $request->manager()->mkdir($path);

        if (!$result) {
            throw ValidationException::withMessages([
                'folder' => [__('nova-file-manager::errors.folder.create')],
            ]);
        }

        event(new FolderCreated($request->manager()->filesystem(), $request->manager()->getDisk(), $path));

        return response()->json([
            'message' => __('nova-file-manager::messages.folder.create'),
        ]);
    }

    /**
     * Rename a folder
     *
     * @param  \Everzel\NovaFileManager\Http\Requests\RenameFolderRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function rename(RenameFolderRequest $request): JsonResponse
    {
        $from = $request->from;
        $to = $request->to;

        event(new FolderRenaming($request->manager()->filesystem(), $request->manager()->getDisk(), $from, $to));

        $result = $request->manager()->rename($from, $to);

        if (!$result) {
            throw ValidationException::withMessages([
                'folder' => [__('nova-file-manager::errors.folder.rename')],
            ]);
        }

        event(new FolderRenamed($request->manager()->filesystem(), $request->manager()->getDisk(), $from, $to));

        return response()->json([
            'message' => __('nova-file-manager::messages.folder.rename'),
        ]);
    }

    /**
     * Delete a folder
     *
     * @param  \Everzel\NovaFileManager\Http\Requests\DeleteFolderRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(DeleteFolderRequest $request): JsonResponse
    {
        $path = $request->path;

        event(new FolderDeleting($request->manager()->filesystem(), $request->manager()->getDisk(), $path));

        $result = $request->manager()->rmdir($path);

        if (!$result) {
            throw ValidationException::withMessages([
                'folder' => [__('nova-file-manager::errors.folder.delete')],
            ]);
        }

        event(new FolderDeleted($request->manager()->filesystem(), $request->manager()->getDisk(), $path));

        return response()->json([
            'message' => __('nova-file-manager::messages.folder.delete'),
        ]);
    }
}
