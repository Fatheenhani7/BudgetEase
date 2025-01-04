<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

=======

class Conversation extends Model
{
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    protected $fillable = [
        'product_id',
        'buyer_id',
        'seller_id',
    ];

    public function product()
    {
<<<<<<< HEAD
        return $this->belongsTo(Product::class)->withDefault(null);
=======
        return $this->belongsTo(Product::class);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
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
