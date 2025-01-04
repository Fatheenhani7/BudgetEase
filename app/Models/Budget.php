<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
use App\Models\UserInfo;
=======
use App\Models\User;
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
use App\Models\Expense;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
<<<<<<< HEAD
        'category_name',
        'amount',
        'amount_spent'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_spent' => 'decimal:2',
        'date_created' => 'datetime'
=======
        'category',
        'amount'
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

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
<<<<<<< HEAD

    public function getRemainingAmountAttribute()
    {
        return $this->amount - $this->amount_spent;
    }

    public function getPercentageSpentAttribute()
    {
        if ($this->amount > 0) {
            return ($this->amount_spent / $this->amount) * 100;
        }
        return 0;
    }
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
}
