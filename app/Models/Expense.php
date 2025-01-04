<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
<<<<<<< HEAD
=======
use App\Models\User;
use App\Models\Budget;
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'budget_id',
<<<<<<< HEAD
        'expense_name',
        'amount',
        'date',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'date' => 'datetime',
=======
        'name',
        'amount'
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class);
    }
}
