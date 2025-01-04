<?php

namespace App\Models;

<<<<<<< HEAD
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Budget;
use App\Models\Expense;
use App\Models\Income;
use App\Models\UserProfile;
use App\Models\Product;
use App\Models\Conversation;
use App\Models\Message;

class UserInfo extends Authenticatable
{
    use HasFactory;

    protected $table = 'users_info';
=======
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\UserProfile;

class UserInfo extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users_info';
    
    // Disable Laravel's timestamp fields
    public $timestamps = false;
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb

    protected $fillable = [
        'username',
        'email',
        'password',
<<<<<<< HEAD
        'is_admin',
        'is_verified_seller',
        'verification_status'
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    ];

    protected $hidden = [
        'password',
<<<<<<< HEAD
        'remember_token',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_verified_seller' => 'boolean',
        'verified_at' => 'datetime'
    ];

    public function budgets()
    {
        return $this->hasMany(Budget::class, 'user_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'user_id');
    }

    public function incomes()
    {
        return $this->hasMany(Income::class, 'user_id');
    }

    public function totalIncome()
    {
        return $this->incomes()->sum('amount');
    }

    public function totalExpenses()
    {
        return $this->expenses()->sum('amount');
    }

=======
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the user's profile.
     */
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    public function profile()
    {
        return $this->hasOne(UserProfile::class, 'user_id');
    }
<<<<<<< HEAD

    public function products()
    {
        return $this->hasMany(Product::class, 'seller_id');
    }

    public function conversations()
    {
        return Conversation::where(function($query) {
                $query->where('buyer_id', $this->id)
                      ->orWhere('seller_id', $this->id);
            })
            ->with(['product' => function($query) {
                $query->withDefault(null);
            }, 'buyer', 'seller', 'lastMessage'])
            ->latest();
    }

    public function isAdmin()
    {
        return $this->is_admin;
    }

    public function verifyAsSeller()
    {
        $this->update([
            'is_verified_seller' => true,
            'verification_status' => 'approved',
            'verified_at' => now()
        ]);
    }

    public function unverifyAsSeller()
    {
        $this->update([
            'is_verified_seller' => false,
            'verification_status' => 'pending',
            'verified_at' => null
        ]);
    }

    public function unreadMessages()
    {
        return Message::whereHas('conversation', function($query) {
            $query->where(function($q) {
                $q->where('buyer_id', $this->id)
                    ->orWhere('seller_id', $this->id);
            });
        })
        ->where('sender_id', '!=', $this->id)
        ->where('is_read', false)
        ->count();
    }
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
}
