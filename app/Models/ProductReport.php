<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Product;

class ProductReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'reason',
        'description',
        'status',
        'admin_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->from('users_info');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
