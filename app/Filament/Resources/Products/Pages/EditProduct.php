<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\ProductImage;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing images
        $data['images'] = $this->record->images()
            ->orderBy('sort_order')
            ->pluck('image_path')
            ->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Store images temporarily and remove from main data
        if (isset($data['images'])) {
            $this->tempImages = $data['images'];
            unset($data['images']);
        }

        return $data;
    }

    protected function afterSave(): void
    {
        // Update product images
        if (isset($this->tempImages)) {
            // Delete old images from database (files remain in storage for now)
            $this->record->images()->delete();

            // Add new images
            foreach ($this->tempImages as $index => $imagePath) {
                ProductImage::create([
                    'product_id' => $this->record->id,
                    'image_path' => $imagePath,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
        }
    }

    protected array $tempImages = [];
}