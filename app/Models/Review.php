<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'first_name',
        'last_name',
        'rating',
        'review_text',
        'is_approved',
    ];

    protected $casts = [
        'rating' => 'decimal:1',
        'is_approved' => 'boolean',
    ];

    // Relationships
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->first_name . ($this->last_name ? ' ' . $this->last_name : '');
    }

    public function getInitialsAttribute()
    {
        $initials = strtoupper(substr($this->first_name, 0, 1));
        if ($this->last_name) {
            $initials .= strtoupper(substr($this->last_name, 0, 1));
        }
        return $initials;
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}