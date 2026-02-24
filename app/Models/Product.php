<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'sku',
        'price',
        'compare_at_price',
        'type',
        'inventory_qty',
        'pdf_file_path',
        'short_description',
        'long_description',
        'is_featured',
        'is_new_arrival',
        'coupon_percentage',
        'coupon_usage_limit',
        'coupon_usage_count',
        'coupon_code',
        'coupon_valid_from',
        'coupon_valid_until',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_at_price' => 'decimal:2',
        'coupon_percentage' => 'decimal:2',
        'coupon_valid_from' => 'datetime',
        'coupon_valid_until' => 'datetime',
        'is_featured' => 'boolean',
        'is_new_arrival' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Auto-generate slug from title
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->title);
            }
        });
    }

    // Relationships
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    // Helper methods
    public function getPriceFormatted()
    {
        return '£' . number_format($this->price, 2);
    }

    public function getComparePriceFormatted()
    {
        return $this->compare_at_price ? '£' . number_format($this->compare_at_price, 2) : null;
    }

    public function getDiscountPercentage()
    {
        if ($this->compare_at_price && $this->compare_at_price > $this->price) {
            return round((($this->compare_at_price - $this->price) / $this->compare_at_price) * 100);
        }
        return 0;
    }

    public function hasStock($quantity = 1)
    {
        if ($this->type === 'pdf') {
            return true; // Digital products always in stock
        }
        return $this->inventory_qty >= $quantity;
    }

    public function hasCoupon()
    {
        if (!$this->coupon_percentage) {
            return false;
        }

        // Check usage limit
        if ($this->coupon_usage_limit && $this->coupon_usage_count >= $this->coupon_usage_limit) {
            return false;
        }

        // Check validity dates
        $now = now();
        if ($this->coupon_valid_from && $now->lt($this->coupon_valid_from)) {
            return false;
        }

        if ($this->coupon_valid_until && $now->gt($this->coupon_valid_until)) {
            return false;
        }

        return true;
    }

    public function validateProductCoupon($code)
    {
        if (!$this->coupon_code) {
            return ['valid' => false, 'message' => 'This product does not have a coupon'];
        }

        if (strtoupper($code) !== strtoupper($this->coupon_code)) {
            return ['valid' => false, 'message' => 'Invalid coupon code for this product'];
        }

        if (!$this->hasCoupon()) {
            return ['valid' => false, 'message' => 'This coupon has expired or reached usage limit'];
        }

        return [
            'valid' => true,
            'discount_percentage' => $this->coupon_percentage,
            'discounted_price' => $this->getPriceAfterCoupon()
        ];
    }

    public function getPriceAfterCoupon()
    {
        if ($this->hasCoupon()) {
            $discount = ($this->price * $this->coupon_percentage) / 100;
            return $this->price - $discount;
        }
        return $this->price;
    }

    // Review statistics
    public function getAverageRating()
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getTotalReviews()
    {
        return $this->approvedReviews()->count();
    }

    public function getRatingBreakdown()
    {
        $total = $this->getTotalReviews();
        if ($total === 0) {
            return ['5' => 0, '4.5' => 0, '4' => 0, '3' => 0, '2' => 0, '1' => 0];
        }

        $breakdown = $this->approvedReviews()
            ->selectRaw('FLOOR(rating) as star, COUNT(*) as count')
            ->groupBy('star')
            ->pluck('count', 'star')
            ->toArray();

        return [
            '5' => round((($breakdown[5] ?? 0) / $total) * 100),
            '4.5' => round((($breakdown[4] ?? 0) / $total) * 100),
            '4' => round((($breakdown[4] ?? 0) / $total) * 100),
            '3' => round((($breakdown[3] ?? 0) / $total) * 100),
            '2' => round((($breakdown[2] ?? 0) / $total) * 100),
            '1' => round((($breakdown[1] ?? 0) / $total) * 100),
        ];
    }
}