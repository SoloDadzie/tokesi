<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'delivery_time',
        'country',
        'state',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForLocation($query, $country, $state = null)
    {
        return $query->where(function ($q) use ($country, $state) {
            // Exact match: country AND state
            $q->where(function ($subQ) use ($country, $state) {
                $subQ->where('country', $country)
                     ->where('state', $state);
            })
            // Country match with no state restriction
            ->orWhere(function ($subQ) use ($country) {
                $subQ->where('country', $country)
                     ->whereNull('state');
            })
            // Global shipping (no country/state restriction)
            ->orWhere(function ($subQ) {
                $subQ->whereNull('country')
                     ->whereNull('state');
            });
        })->orderBy('sort_order');
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return '£' . number_format($this->price, 2);
    }

    public function isApplicableFor($country, $state = null)
    {
        // Global shipping method
        if (!$this->country && !$this->state) {
            return true;
        }

        // Country-specific, no state restriction
        if ($this->country === $country && !$this->state) {
            return true;
        }

        // Country and state specific
        if ($this->country === $country && $this->state === $state) {
            return true;
        }

        return false;
    }
}