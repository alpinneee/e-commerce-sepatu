<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'total_amount',
        'shipping_cost',
        'discount_amount',
        'cod_fee',
        'payment_method',
        'payment_status',
        'payment_details',
        'payment_proof',
        'payment_instructions',
        'payment_proof_uploaded_at',
        'shipping_address',
        'shipping_expedition',
        'shipping_expedition_name',
        'shipping_estimation',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'total_amount' => 'decimal:0',
        'shipping_cost' => 'decimal:0',
        'discount_amount' => 'decimal:0',
        'cod_fee' => 'decimal:0',
        'payment_details' => 'json',
        'shipping_address' => 'json',
        'payment_proof_uploaded_at' => 'datetime',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the subtotal amount (total without shipping, discount, and COD fee).
     */
    public function getSubtotalAttribute()
    {
        return $this->total_amount + $this->discount_amount - $this->shipping_cost - $this->cod_fee;
    }

    /**
     * Get the shipping address as an object.
     */
    public function getShippingAddressObjectAttribute()
    {
        return json_decode($this->shipping_address);
    }

    /**
     * Get the payment details as an object.
     */
    public function getPaymentDetailsObjectAttribute()
    {
        return json_decode($this->payment_details);
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'delivered' => 'Terkirim',
            'cancelled' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Get the payment status label.
     */
    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => 'Menunggu',
            'paid' => 'Dibayar',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    /**
     * Get the payment method label.
     */
    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            'qris' => 'QRIS',
            'gopay' => 'GoPay',
            'ovo' => 'OVO',
            'dana' => 'DANA',
            'shopeepay' => 'ShopeePay',
            'bca' => 'Bank BCA',
            'mandiri' => 'Bank Mandiri',
            'bri' => 'Bank BRI',
            'bni' => 'Bank BNI',
            'cod' => 'Cash on Delivery (COD)',
        ];

        return $labels[$this->payment_method] ?? $this->payment_method;
    }
}
