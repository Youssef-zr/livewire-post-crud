<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class ImageUpload
{
    // store new image
    public static function store($imgProps)
    {
        $file = $imgProps['file'];
        $width = $imgProps['width'] ?? null;
        $height = $imgProps['height'] ?? null;
        $quality = $imgProps['quality'] ?? 100;
        $storagePath = $imgProps['storagePath'];

        // Resize the image
        $img = Image::make($file)->resize($width, $height, function ($constraint) use ($width, $height) {
            if ($width && !$height) {
                $constraint->aspectRatio();
            } elseif (!$width && $height) {
                $constraint->aspectRatio();
            }
        });

        $file_name = $file->hashName();
        $path = $storagePath . '/' . $file_name;
        $img->save(public_path($path), $quality, "webp");

        $fileInformation = [
            'original_name' => $file->getClientOriginalName(),
            'file_name' => $file_name,
            'file_extension' => $file->extension(),
            'file_size' => $file->getSize(),
            'file_path' => $path,
        ];

        return $fileInformation;
    }

    // Update existing file
    public static function update($imgProps)
    {
        self::delete($imgProps);
        $fileInformation = self::store($imgProps);

        return $fileInformation;
    }

    // Remove existing image
    public static function delete($imgProps)
    {
        $oldFilePath = $imgProps['old_image'];
        $default = $imgProps['default'];

        if (File::exists($oldFilePath) && strpos($default, "default.") === false) {
            @unlink(public_path($oldFilePath));
        }
    }
}
