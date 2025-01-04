<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketplaceCategory extends Model
{
    use HasFactory;

    protected $table = 'marketplace_categories';
<<<<<<< HEAD
    public $timestamps = false;
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb

    protected $fillable = [
        'name',
        'description'
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'category', 'name');
    }
}
