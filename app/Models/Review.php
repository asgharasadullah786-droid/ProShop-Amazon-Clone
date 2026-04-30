<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'rating', 'comment', 
        'images', 'is_approved', 'has_images'
    ];

    protected $casts = [
        'images' => 'array',
        'is_approved' => 'boolean',
        'has_images' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getImageUrlsAttribute()
    {
        if (!$this->has_images || !$this->images) {
            return [];
        }
        
        return array_map(function($image) {
            return asset('uploads/reviews/' . $image);
        }, $this->images);
    }
}