<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'discount_price',
        'sku',
        'stock',
        'weight',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:0',
        'discount_price' => 'decimal:0',
        'weight' => 'decimal:0',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the images for the product.
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get the reviews for the product.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the wishlist items for the product.
     */
    public function wishlistItems()
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * Get the users who have this product in their wishlist.
     */
    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlists');
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include products in stock.
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Scope a query to only include products on sale.
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('discount_price')->where('discount_price', '>', 0);
    }

    /**
     * Scope a query to filter products by price range.
     */
    public function scopePriceRange($query, $minPrice, $maxPrice)
    {
        if ($minPrice !== null) {
            $query->where(function($q) use ($minPrice) {
                $q->where('price', '>=', $minPrice)
                  ->orWhere('discount_price', '>=', $minPrice);
            });
        }

        if ($maxPrice !== null) {
            $query->where(function($q) use ($maxPrice) {
                $q->where('discount_price', '<=', $maxPrice)
                  ->orWhereNull('discount_price')
                  ->where('price', '<=', $maxPrice);
            });
        }

        return $query;
    }

    /**
     * Get the primary image of the product.
     */
    public function getPrimaryImageAttribute()
    {
        return $this->images()->where('is_primary', true)->first();
    }

    /**
     * Get the current price of the product (discount price if available, otherwise regular price).
     */
    public function getCurrentPriceAttribute()
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Get the discount percentage of the product.
     */
    public function getDiscountPercentageAttribute()
    {
        if (!$this->discount_price || $this->price <= 0) {
            return 0;
        }

        return round((($this->price - $this->discount_price) / $this->price) * 100);
    }
}
