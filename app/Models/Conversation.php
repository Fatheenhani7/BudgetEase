<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class)->withDefault(null);
    }

    public function buyer()
    {
        return $this->belongsTo(UserInfo::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(UserInfo::class, 'seller_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at', 'asc');
    }

    public function lastMessage()
    {
        return $this->hasOne(Message::class)->latest();
    }

    public function unreadMessages()
    {
        return $this->messages()->where('is_read', false);
    }
}
