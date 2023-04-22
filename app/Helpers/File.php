<?php

namespace App\Helpers;

use App\Models\File as FileModel;
use Illuminate\Support\Facades\Storage;

class File
{
    /**
     * Available origins
     *
     * @var array|string[]
     */
    static array $origins = ['public', 'external'];

    /**
     * Store or update a file to server.
     *
     * @param $saveRequest
     * @return mixed
     */
    public static function save($saveRequest): mixed
    {
        if (!in_array($saveRequest['origin'], self::$origins)) {
            return false;
        }

        if ($id = $saveRequest['id'] ?? null) {
            $file = FileModel::find($id);

            self::deleteFile($file->path . $file->hash_name);
        }

        $saveRequest['file']->store($saveRequest['path']);

        $filter = [
            'id' => $id
        ];
        $data = [
            'origin' => $saveRequest['origin'],
            'original_name' => $saveRequest['file']->getClientOriginalName(),
            'hash_name' => $saveRequest['file']->hashName(),
            'path' => $saveRequest['path'],
            'extension' => $saveRequest['file']->getClientOriginalExtension(),
            'mime_type' => $saveRequest['file']->getClientMimeType(),
            'size' => $saveRequest['file']->getSize(),
        ];

        return FileModel::updateOrCreate($filter, $data);
    }

    /**
     * Delete a file & data in database from server.
     *
     * @param int $id
     * @return mixed
     */
    public static function delete(int $id): mixed
    {
        $file = FileModel::find($id);

        self::deleteFile($file->path . $file->hash_name);

        return $file->delete();
    }

    /**
     * Delete file from server.
     *
     * @param string $path
     * @return void
     */
    private static function deleteFile(string $path): void
    {
        if ($path && Storage::exists($path)) {
            Storage::delete($path);
        }
    }
}
