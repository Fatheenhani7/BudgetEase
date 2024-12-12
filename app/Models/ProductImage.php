<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'product_id',
        'image_url',
        'is_primary'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
