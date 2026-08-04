<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageCompressionService
{
    /**
     * Store and compress uploaded image automatically if GD library is available,
     * otherwise store normally with clean file paths.
     */
    public static function compressAndStore(UploadedFile $file, string $folder, string $disk = 'public', int $maxWidth = 1600, int $quality = 82): string
    {
        // Check if GD extension is available for compression
        if (extension_loaded('gd')) {
            try {
                $imageInfo = getimagesize($file->getRealPath());
                if ($imageInfo !== false) {
                    $mime = $imageInfo['mime'];
                    $width = $imageInfo[0];
                    $height = $imageInfo[1];

                    // Create image resource based on mime type
                    $srcImage = match ($mime) {
                        'image/jpeg', 'image/jpg' => imagecreatefromjpeg($file->getRealPath()),
                        'image/png' => imagecreatefrompng($file->getRealPath()),
                        'image/webp' => imagecreatefromwebp($file->getRealPath()),
                        default => null,
                    };

                    if ($srcImage) {
                        // Calculate new dimensions if image exceeds maxWidth
                        if ($width > $maxWidth) {
                            $newWidth = $maxWidth;
                            $newHeight = (int) round(($height / $width) * $newWidth);
                        } else {
                            $newWidth = $width;
                            $newHeight = $height;
                        }

                        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

                        // Preserve alpha transparency for PNG / WebP
                        if ($mime === 'image/png' || $mime === 'image/webp') {
                            imagealphablending($dstImage, false);
                            imagesavealpha($dstImage, true);
                        }

                        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

                        // Generate unique target filename
                        $extension = strtolower($file->getClientOriginalExtension());
                        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
                            $extension = 'jpg';
                        }
                        $filename = uniqid('img_', true) . '.' . $extension;
                        $relativePath = trim($folder, '/') . '/' . $filename;

                        ob_start();
                        match ($extension) {
                            'png' => imagepng($dstImage, null, 7),
                            'webp' => imagewebp($dstImage, null, $quality),
                            default => imagejpeg($dstImage, null, $quality),
                        };
                        $imageData = ob_get_clean();

                        imagedestroy($srcImage);
                        imagedestroy($dstImage);

                        Storage::disk($disk)->put($relativePath, $imageData);
                        return $relativePath;
                    }
                }
            } catch (\Throwable $e) {
                // Fallback to normal store on any error
            }
        }

        // Standard Laravel file store fallback
        return $file->store($folder, $disk);
    }
}
