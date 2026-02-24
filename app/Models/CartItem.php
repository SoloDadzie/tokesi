<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

// CartItem Model
class CartItem extends Model
{
    protected $fillable = [
        'session_id',
        'product_id',
        'quantity',
        'price',
        'applied_discount',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotal()
    {
        return $this->price * $this->quantity;
    }

    public function getAppliedDiscount()
    {
        return $this->applied_discount ? json_decode($this->applied_discount, true) : null;
    }
}