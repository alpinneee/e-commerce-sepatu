<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'is_default',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the shipping address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Set this address as the default and unset others.
     */
    public function setAsDefault()
    {
        $this->where('user_id', $this->user_id)
            ->where('id', '!=', $this->id)
            ->update(['is_default' => false]);
    }

    /**
     * Get the full address as a string.
     */
    public function getFullAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->province} {$this->postal_code}";
    }
}
