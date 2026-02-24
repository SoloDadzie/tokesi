<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'first_name',
        'last_name',
        'email',
        'phone',
        'shipping_address',
        'city',
        'state',
        'zipcode',
        'country',
        'message',
        'shipping_method',
        'shipping_cost',
        'subtotal',
        'discount_amount',
        'coupon_code',
        'total',
        'payment_method',
        'payment_status',
        'payment_intent_id',
        'transaction_id',
        'status',
        'tracking_number',
        'tracking_link',
        'shipping_company',
        'paid_at',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total' => 'decimal:2',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // Add this: Define attributes that should NOT be saved to database
    protected $appends = [];
    
    // Temporary flag for address changes (not saved to DB)
    public $addressChanged = false;

    // Boot method to register events
    protected static function boot()
    {
        parent::boot();

        // Listen for updates
        static::updating(function ($order) {
            // Track what changed
            $order->trackStatusChanges();
            $order->trackAddressChanges();
        });
    }

    // Track status changes for email notifications
    protected function trackStatusChanges()
    {
        if ($this->isDirty('status')) {
            $oldStatus = $this->getOriginal('status');
            $newStatus = $this->status;

            // Set shipped_at timestamp when status changes to shipped
            if ($newStatus === 'shipped' && $oldStatus !== 'shipped') {
                $this->shipped_at = now();
            }

            // Set delivered_at timestamp when status changes to delivered
            if ($newStatus === 'delivered' && $oldStatus !== 'delivered') {
                $this->delivered_at = now();
            }
        }
    }

    // Track address changes for email notifications
    protected function trackAddressChanges()
    {
        $addressFields = ['email', 'shipping_address', 'city', 'state', 'zipcode', 'country'];
        
        foreach ($addressFields as $field) {
            if ($this->isDirty($field)) {
                // Mark that address was changed (won't be saved to DB)
                $this->addressChanged = true;
                break;
            }
        }
    }

    // Relationships
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFormattedTotalAttribute()
    {
        return '£' . number_format($this->total, 2);
    }

    public function getStatusBadgeColorAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'processing' => 'info',
            'shipped' => 'primary',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function canBeCancelled()
    {
        return !in_array($this->status, ['shipped', 'delivered', 'cancelled']);
    }

    public function canUpdateAddress()
    {
        return !in_array($this->status, ['shipped', 'delivered', 'cancelled']);
    }

    // Generate unique order number
    public static function generateOrderNumber()
    {
        do {
            $number = 'ORD-' . strtoupper(uniqid());
        } while (self::where('order_number', $number)->exists());
        
        return $number;
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }
}