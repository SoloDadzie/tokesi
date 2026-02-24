<?php

namespace App\Filament\Resources\Media\Pages;

use App\Filament\Resources\Media\MediaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle bulk upload - if path is an array, we'll create multiple records
        if (isset($data['path']) && is_array($data['path'])) {
            $files = $data['path'];
            $optimizeToWebp = $data['optimize_to_webp'] ?? true;
            
            // Process first file in this method, others will be created after
            $firstFile = array_shift($files);
            
            // Store remaining files for afterCreate hook
            $this->remainingFiles = $files;
            $this->bulkData = $data;
            $this->optimizeToWebp = $optimizeToWebp;
            
            // Process single file with the first path
            $data['path'] = $firstFile;
        } else {
            $this->remainingFiles = [];
        }

        // Process single file
        return $this->processFile($data);
    }

    protected array $remainingFiles = [];
    protected array $bulkData = [];
    protected bool $optimizeToWebp = true;

    protected function processFile(array $data): array
    {
        // CRITICAL: Ensure path exists and is a string
        if (!isset($data['path']) || empty($data['path'])) {
            throw new \Exception('File path is missing');
        }

        // If path is still an array (shouldn't happen, but safety check)
        if (is_array($data['path'])) {
            $data['path'] = $data['path'][0];
        }

        // EXTRACT FILE METADATA - This is what was missing!
        try {
            if (!Storage::disk('public')->exists($data['path'])) {
                throw new \Exception("File not found in storage: {$data['path']}");
            }

            $fullPath = Storage::disk('public')->path($data['path']);
            $fileInfo = pathinfo($fullPath);
            
            // Extract basic file info
            $data['original_filename'] = $fileInfo['basename'];
            $data['filename'] = $fileInfo['basename'];
            $data['size'] = filesize($fullPath);
            
            // Get mime type
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $data['mime_type'] = finfo_file($finfo, $fullPath);
            finfo_close($finfo);
            
            // Determine type from mime
            if (str_starts_with($data['mime_type'], 'image/')) {
                $data['type'] = 'image';
            } elseif (str_starts_with($data['mime_type'], 'video/')) {
                $data['type'] = 'video';
            } else {
                $data['type'] = 'document';
            }

            // Set disk
            $data['disk'] = 'public';

        } catch (\Exception $e) {
            Log::error('File metadata extraction error', [
                'error' => $e->getMessage(),
                'path' => $data['path'] ?? 'unknown'
            ]);
            throw $e;
        }

        // Get image dimensions and optimize if it's an image
        if ($data['type'] === 'image') {
            try {
                $fullPath = Storage::disk('public')->path($data['path']);
                $manager = new ImageManager(new Driver());
                $image = $manager->read($fullPath);
                
                $data['width'] = $image->width();
                $data['height'] = $image->height();
                $data['dimensions'] = $data['width'] . 'x' . $data['height'];
                
                // WebP Optimization
                if (($this->optimizeToWebp || $data['optimize_to_webp'] ?? false) && 
                    !str_ends_with($data['path'], '.webp')) {
                    
                    // Create new WebP filename
                    $newFilename = Str::replaceLast(
                        '.' . pathinfo($data['path'], PATHINFO_EXTENSION),
                        '.webp',
                        $data['path']
                    );
                    
                    // Save as WebP with 85% quality (good balance)
                    $newPath = Storage::disk('public')->path($newFilename);
                    $image->toWebp(85)->save($newPath);
                    
                    // Delete original file
                    Storage::disk('public')->delete($data['path']);
                    
                    // Update data
                    $oldSize = $data['size'];
                    $data['path'] = $newFilename;
                    $data['filename'] = basename($newFilename);
                    $data['original_filename'] = basename($newFilename);
                    $data['mime_type'] = 'image/webp';
                    $data['size'] = filesize($newPath);
                    
                    // Calculate compression percentage
                    $compressionPercent = round((1 - ($data['size'] / $oldSize)) * 100);
                    
                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Image Optimized')
                        ->body("Converted to WebP - {$compressionPercent}% smaller!")
                        ->send();
                }
                
                // Calculate human-readable size
                $data['size_human'] = $this->formatBytes($data['size']);
                
            } catch (\Exception $e) {
                Log::error('Image processing error', [
                    'error' => $e->getMessage(),
                    'path' => $data['path'] ?? 'unknown',
                    'trace' => $e->getTraceAsString()
                ]);
                
                \Filament\Notifications\Notification::make()
                    ->warning()
                    ->title('Processing Warning')
                    ->body('Could not process image: ' . $e->getMessage())
                    ->send();
            }
        } else {
            // For non-images, still calculate human-readable size
            $data['size_human'] = $this->formatBytes($data['size']);
        }

        // Remove the optimize_to_webp field as it's not in the database
        unset($data['optimize_to_webp']);

        return $data;
    }

    protected function afterCreate(): void
    {
        // Handle bulk upload - create records for remaining files
        if (!empty($this->remainingFiles)) {
            $successCount = 0;
            $failCount = 0;

            foreach ($this->remainingFiles as $file) {
                try {
                    // Create a fresh data array for this file
                    $fileData = [
                        'path' => $file,
                        'optimize_to_webp' => $this->optimizeToWebp,
                        'alt_text' => $this->bulkData['alt_text'] ?? null,
                        'caption' => $this->bulkData['caption'] ?? null,
                        'collection' => $this->bulkData['collection'] ?? 'general',
                        'tags' => $this->bulkData['tags'] ?? [],
                    ];
                    
                    // Process the file (this extracts all metadata)
                    $processedData = $this->processFile($fileData);
                    
                    // Create the record
                    static::getModel()::create($processedData);
                    
                    $successCount++;
                    
                } catch (\Exception $e) {
                    $failCount++;
                    Log::error('Bulk upload file processing error', [
                        'file' => $file,
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            if ($successCount > 0) {
                \Filament\Notifications\Notification::make()
                    ->success()
                    ->title('Bulk Upload Complete')
                    ->body("{$successCount} file(s) uploaded successfully!" . ($failCount > 0 ? " ({$failCount} failed)" : ''))
                    ->send();
            }

            if ($failCount > 0 && $successCount === 0) {
                \Filament\Notifications\Notification::make()
                    ->danger()
                    ->title('Upload Failed')
                    ->body("Failed to upload {$failCount} file(s). Check logs for details.")
                    ->send();
            }
        }
    }

    protected function formatBytes(int $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}