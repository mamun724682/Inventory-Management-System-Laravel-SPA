<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileManagerService
{
    /**
     * Upload file and save file name by given name
     *
     * @param object $file
     * @param string $uploadPath
     * @param string|null $fileName
     * @param string|null $deleteFileName
     * @return string|null
     */
    public function uploadFile(
        object $file,
        string $uploadPath = 'others',
        string $fileName = null,
        string $deleteFileName = null,
    ): string|null
    {
        try {
            // Delete old file
            if ($deleteFileName) {
                $this->delete(
                    fileName: $deleteFileName,
                    path: $uploadPath
                );
            }

            // Upload new file
            return $this->upload(
                file: $file,
                path: $uploadPath,
                fileName: $fileName ?? $file->getClientOriginalName()
            );
        } catch (Exception $ex) {
            return null;
        }
    }

    /**
     * @param object $file
     * @param string $path
     * @param string|null $fileName
     * @return string
     */
    private function upload(
        object $file,
        string $path,
        string $fileName = null
    ): string
    {
        try {
            if ($fileName) {
                $name = Str::slug($fileName) . '-' . time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
            } else {
                $name = time() . rand(1111, 9999) . '.' . $file->getClientOriginalExtension();
            }

            // Store file to public disk
            $file->storeAs($path, $name);
            return $name ?? '';
        } catch (Exception $ex) {
            return '';
        }
    }

    /**
     * @param string $fileName
     * @param string $path
     * @return bool
     */
    public function delete(string $fileName, string $path = "others"): bool
    {
        if (Storage::exists("{$path}/$fileName")) {
            Storage::delete("{$path}/$fileName");
            return true;
        }

        return false;
    }
}
