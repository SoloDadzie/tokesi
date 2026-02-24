<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use App\Models\ProductImage;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Store images temporarily and remove from main data
        if (isset($data['images'])) {
            $this->tempImages = $data['images'];
            unset($data['images']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        // Save product images after product is created
        if (!empty($this->tempImages)) {
            foreach ($this->tempImages as $index => $imagePath) {
                ProductImage::create([
                    'product_id' => $this->record->id,
                    'image_path' => $imagePath,
                    'sort_order' => $index,
                    'is_primary' => $index === 0, // First image is primary
                ]);
            }
        }
    }

    protected array $tempImages = [];
}
