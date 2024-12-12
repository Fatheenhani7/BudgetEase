<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;

// Guest routes
Route::middleware('guest')->group(function () {
    // Redirect root to login if not authenticated
    Route::get('/', function () {
        return redirect()->route('login');
    });

    // Auth Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Home route (only accessible after login)
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Logout route
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Budget routes
    Route::get('/budgets', [BudgetController::class, 'index'])->name('budgets.index');
    Route::get('/budgets/create', [BudgetController::class, 'create'])->name('budgets.create');
    Route::post('/budgets', [BudgetController::class, 'store'])->name('budgets.store');
    Route::get('/budgets/{budget}', [BudgetController::class, 'show'])->name('budgets.show');
    Route::put('/budgets/{budget}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/budgets/{budget}', [BudgetController::class, 'destroy'])->name('budgets.destroy');

    // Expense routes
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}', [ExpenseController::class, 'show'])->name('expenses.show');
    Route::get('/expenses/{expense}/edit', [ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Marketplace routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace.index');
        Route::get('/marketplace/create', [MarketplaceController::class, 'create'])->name('marketplace.create');
        Route::post('/marketplace', [MarketplaceController::class, 'store'])->name('marketplace.store');
        Route::get('/marketplace/{product}', [MarketplaceController::class, 'show'])->name('marketplace.show');
        Route::get('/marketplace/{product}/edit', [MarketplaceController::class, 'edit'])->name('marketplace.edit');
        Route::put('/marketplace/{product}', [MarketplaceController::class, 'update'])->name('marketplace.update');
        Route::delete('/marketplace/{product}', [MarketplaceController::class, 'destroy'])->name('marketplace.destroy');
        Route::get('/marketplace/seller/{seller}', [MarketplaceController::class, 'sellerProducts'])->name('marketplace.seller.products');
    });

    // Chat routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
        Route::get('/chat/{conversation}', [ChatController::class, 'show'])->name('chat.show');
        Route::post('/chat/product/{product}', [ChatController::class, 'store'])->name('chat.store');
        Route::post('/chat/{conversation}/message', [ChatController::class, 'sendMessage'])->name('chat.message');
    });

    // Profile routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
        Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
        
        // Product Management Routes
        Route::get('/profile/products/{id}/edit', [ProfileController::class, 'editProduct'])->name('profile.edit-product');
        Route::put('/profile/products/{id}', [ProfileController::class, 'updateProduct'])->name('profile.update-product');
        Route::delete('/profile/products/{id}', [ProfileController::class, 'deleteProduct'])->name('profile.delete-product');
        
        Route::get('/profile/product/{id}/edit', [ProfileController::class, 'editProduct'])->name('profile.product.edit');
        Route::put('/profile/product/{id}', [ProfileController::class, 'updateProduct'])->name('profile.product.update');
    });
});
