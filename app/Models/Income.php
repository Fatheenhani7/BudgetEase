<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use App\Models\User;
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb

class Income extends Model
{
    use HasFactory;

<<<<<<< HEAD
    protected $table = 'incomes';

    protected $fillable = [
        'user_id',
        'amount',
        'description',
        'category',
        'date'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'datetime'
=======
    protected $fillable = [
        'user_id',
        'amount',
        'source'
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    ];

    public function user()
    {
<<<<<<< HEAD
        return $this->belongsTo(UserInfo::class);
=======
        return $this->belongsTo(User::class);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }
}
