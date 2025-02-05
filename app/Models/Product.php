<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use App\Models\Conversation;
use App\Models\Message;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\ProductReport;
use App\Models\UserInfo;
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'seller_id',
        'title',
        'description',
        'price',
        'category',
        'condition_status',
<<<<<<< HEAD
        'location',
        'average_rating',
        'rating_count',
        'is_top_seller'
    ];

    protected static function boot()
    {
        parent::boot();

        // When a product is deleted, also delete related records
        static::deleting(function($product) {
            // Delete images
            $product->images()->delete();
            
            // Delete conversations and their messages
            $conversations = Conversation::where('product_id', $product->id)->get();
            foreach($conversations as $conversation) {
                $conversation->messages()->delete();
                $conversation->delete();
            }
            
            // Delete ratings
            $product->ratings()->delete();
            
            // Delete reports
            $product->reports()->delete();
        });
    }

=======
        'location'
    ];

>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    public function seller()
    {
        return $this->belongsTo(UserInfo::class, 'seller_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

<<<<<<< HEAD
    public function conversations()
    {
        return $this->hasMany(Conversation::class);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function reports()
    {
        return $this->hasMany(ProductReport::class);
=======
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function transactions()
    {
        return $this->hasMany(MarketplaceTransaction::class);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    public function getPrimaryImageUrlAttribute()
    {
        return $this->primaryImage?->image_url ?? 'images/placeholder.jpg';
    }
<<<<<<< HEAD

    public function getSellerVerificationStatusAttribute()
    {
        return $this->seller->is_verified_seller ? 'Verified Seller' : '';
    }

    public function getSellerBadgesAttribute()
    {
        $badges = [];
        if ($this->seller->is_verified_seller) {
            $badges[] = 'Verified Seller';
        }
        if ($this->is_top_seller) {
            $badges[] = 'Top Seller';
        }
        return $badges;
    }

    public function calculateAverageRating()
    {
        $ratings = $this->ratings;
        if ($ratings->isEmpty()) {
            return 0;
        }
        return round($ratings->avg('rating'), 1);
    }
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
}
