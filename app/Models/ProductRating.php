<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'review'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::created(function ($rating) {
            $product = $rating->product;
            
            // Update product's average rating and count
            $avgRating = $product->ratings()->avg('rating');
            $ratingCount = $product->ratings()->count();
            
            $product->update([
                'average_rating' => $avgRating,
                'rating_count' => $ratingCount,
                'is_top_seller' => $avgRating >= 4.0
            ]);
        });

        static::updated(function ($rating) {
            $product = $rating->product;
            
            // Update product's average rating
            $avgRating = $product->ratings()->avg('rating');
            $product->update([
                'average_rating' => $avgRating,
                'is_top_seller' => $avgRating >= 4.0
            ]);
        });

        static::deleted(function ($rating) {
            $product = $rating->product;
            
            // Update product's average rating and count
            $avgRating = $product->ratings()->avg('rating') ?? 0;
            $ratingCount = $product->ratings()->count();
            
            $product->update([
                'average_rating' => $avgRating,
                'rating_count' => $ratingCount,
                'is_top_seller' => $avgRating >= 4.0
            ]);
        });
    }
}
