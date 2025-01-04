<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\ProductReport;
use App\Models\UserInfo;

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
    }
}
