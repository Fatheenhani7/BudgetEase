<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
<<<<<<< HEAD
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductReport;
use App\Models\UserInfo;
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
<<<<<<< HEAD
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                
                // Get reported products count for sellers
                if ($user->role === 'seller') {
                    $reportedProductsCount = ProductReport::whereHas('product', function($query) use ($user) {
                        $query->where('seller_id', $user->id);
                    })->count();
                    $view->with('reportedProductsCount', $reportedProductsCount);
                }
                
                // Get unread messages count for admin
                if ($user->email === 'adminb@gmail.com') {
                    $adminUser = UserInfo::where('email', 'adminb@gmail.com')->first();
                    $unreadMessagesCount = $adminUser ? $adminUser->unreadMessages() : 0;
                    $view->with('unreadMessagesCount', $unreadMessagesCount);
                }
            }
        });
=======
        //
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }
}
