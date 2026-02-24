<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    protected ImageManager $manager;

    public function __construct()
    {
        $this->manager = new ImageManager(new Driver());
    }

    public function convertToWebP(string $path, int $quality = 90): string
    {
        // Get the full path
        $fullPath = Storage::disk('public')->path($path);
        
        // Read the image
        $image = $this->manager->read($fullPath);
        
        // Generate new filename with .webp extension
        $pathInfo = pathinfo($path);
        $newFilename = $pathInfo['filename'] . '.webp';
        $newPath = $pathInfo['dirname'] . '/' . $newFilename;
        
        // Convert to WebP and save
        $webpImage = $image->toWebp($quality);
        Storage::disk('public')->put($newPath, (string) $webpImage);
        
        // Delete original if it's not already WebP
        if ($pathInfo['extension'] !== 'webp') {
            Storage::disk('public')->delete($path);
        }
        
        return $newPath;
    }

    public function processUploadedImage(string $path, array $options = []): string
    {
        $quality = $options['quality'] ?? 90;
        $maxWidth = $options['max_width'] ?? 1920;
        $maxHeight = $options['max_height'] ?? 1080;

        $fullPath = Storage::disk('public')->path($path);
        $image = $this->manager->read($fullPath);

        // Resize if needed
        if ($image->width() > $maxWidth || $image->height() > $maxHeight) {
            $image->scale(width: $maxWidth, height: $maxHeight);
        }

        // Generate WebP filename
        $pathInfo = pathinfo($path);
        $newFilename = $pathInfo['filename'] . '.webp';
        $newPath = $pathInfo['dirname'] . '/' . $newFilename;

        // Save as WebP
        $webpImage = $image->toWebp($quality);
        Storage::disk('public')->put($newPath, (string) $webpImage);

        // Delete original if different
        if ($pathInfo['extension'] !== 'webp') {
            Storage::disk('public')->delete($path);
        }

        return $newPath;
    }
}