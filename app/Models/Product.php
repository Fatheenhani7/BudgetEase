<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'location'
    ];

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

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function transactions()
    {
        return $this->hasMany(MarketplaceTransaction::class);
    }

    public function getPrimaryImageUrlAttribute()
    {
        return $this->primaryImage?->image_url ?? 'images/placeholder.jpg';
    }
}
