<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserInfo;

class UserProfile extends Model
{
    use HasFactory;

    protected $table = 'user_profile_details';

    protected $fillable = [
        'user_id',
        'profile_picture_url',
        'phone_number',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(UserInfo::class, 'user_id');
    }
}
