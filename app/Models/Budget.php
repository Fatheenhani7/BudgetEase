<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserInfo;
use App\Models\Expense;

class Budget extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_name',
        'amount',
        'amount_spent'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'amount_spent' => 'decimal:2',
        'date_created' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(UserInfo::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

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
}
