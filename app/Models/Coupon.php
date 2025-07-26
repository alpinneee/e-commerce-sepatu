<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'type',
        'value',
        'min_amount',
        'max_discount',
        'starts_at',
        'expires_at',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'value' => 'decimal:0',
        'min_amount' => 'decimal:0',
        'max_discount' => 'decimal:0',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Scope a query to only include active coupons.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include valid coupons by date.
     */
    public function scopeValidByDate($query)
    {
        $now = now();
        return $query->where(function($q) use ($now) {
            $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
        })->where(function($q) use ($now) {
            $q->whereNull('expires_at')->orWhere('expires_at', '>=', $now);
        });
    }

    /**
     * Scope a query to only include coupons with available usage.
     */
    public function scopeHasAvailableUsage($query)
    {
        return $query->where(function($q) {
            $q->whereNull('usage_limit')->orWhereRaw('used_count < usage_limit');
        });
    }

    /**
     * Check if the coupon is valid.
     */
    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();

        // Check start date
        if ($this->starts_at && $this->starts_at->gt($now)) {
            return false;
        }

        // Check expiration date
        if ($this->expires_at && $this->expires_at->lt($now)) {
            return false;
        }

        // Check usage limit
        if ($this->usage_limit !== null && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * Calculate the discount amount for a given subtotal.
     */
    public function calculateDiscount($subtotal)
    {
        if ($subtotal < $this->min_amount) {
            return 0;
        }

        $discount = 0;

        if ($this->type === 'percentage') {
            $discount = $subtotal * ($this->value / 100);
        } else { // fixed
            $discount = $this->value;
        }

        // Apply max discount if set
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }

        // Ensure discount doesn't exceed subtotal
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        return $discount;
    }

    /**
     * Increment the used count.
     */
    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
