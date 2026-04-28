<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'price', 'compare_price',
        'stock', 'sku', 'image', 'images', 'category_id', 'user_id', 'status'
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2'
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper Methods
    public function getDiscountedPriceAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return $this->compare_price - $this->price;
        }
        return 0;
    }

    public function getDiscountPercentAttribute()
    {
        if ($this->compare_price && $this->compare_price > $this->price) {
            return round(($this->discounted_price / $this->compare_price) * 100);
        }
        return 0;
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
    
    public function getStockStatusAttribute()
    {
        if ($this->stock <= 0) {
            return ['class' => 'danger', 'text' => 'Out of Stock'];
        } elseif ($this->stock <= 5) {
            return ['class' => 'warning', 'text' => 'Low Stock (' . $this->stock . ' left)'];
        } else {
            return ['class' => 'success', 'text' => 'In Stock'];
        }
    }
}