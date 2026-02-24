<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// ProductImage Model
class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getUrl()
    {
        return asset('storage/' . $this->image_path);
    }
}