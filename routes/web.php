<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductRatingController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductReportController;

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

// Logout route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/users/{id}', [AdminController::class, 'viewUser'])->name('admin.viewUser');
    Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');

    // Product Reports routes
    Route::get('/reports', [ProductReportController::class, 'adminIndex'])->name('admin.reports.index');
    Route::get('/reports/{report}', [ProductReportController::class, 'adminShow'])->name('admin.reports.show');
    Route::put('/reports/{report}', [ProductReportController::class, 'adminUpdate'])->name('admin.reports.update');
    Route::delete('/products/{id}', [ProductReportController::class, 'deleteProduct'])->name('admin.reports.deleteProduct');
});

// Protected routes
Route::middleware('auth')->group(function () {
    // Home route (only accessible after login)
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Profile routes
    Route::controller(ProfileController::class)
        ->name('profile.')
        ->group(function () {
            Route::get('/profile', 'show')->name('index');
            Route::get('/profile/edit', 'edit')->name('edit');
            Route::put('/profile/update', 'update')->name('update');
            Route::delete('/profile/products/{product}', 'deleteProduct')->name('delete-product');
        });

    // Budget routes
    Route::controller(BudgetController::class)
        ->prefix('budgets')
        ->name('budgets.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{budget}', 'show')->name('show');
            Route::put('/{budget}', 'update')->name('update');
            Route::delete('/{budget}', 'destroy')->name('destroy');
            Route::post('/add-expense', 'addExpense')->name('add-expense');
            Route::delete('/expenses/{expense}', 'destroyExpense')->name('expenses.destroy');
        });

    // Income routes
    Route::controller(IncomeController::class)
        ->prefix('incomes')
        ->name('incomes.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
            Route::delete('/{income}', 'destroy')->name('destroy');
        });

    // Expense routes
    Route::controller(ExpenseController::class)
        ->name('expenses.')
        ->group(function () {
            Route::get('/expenses', 'index')->name('index');
            Route::post('/expenses', 'store')->name('store');
            Route::get('/expenses/{expense}/edit', 'edit')->name('edit');
            Route::put('/expenses/{expense}', 'update')->name('update');
            Route::delete('/expenses/{expense}', 'destroy')->name('destroy');
        });

    // Marketplace routes
    Route::controller(MarketplaceController::class)
        ->prefix('marketplace')
        ->name('marketplace.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/products/{product}', 'show')->name('show');
            Route::get('/products/{product}/edit', 'edit')->name('edit');
            Route::put('/products/{product}', 'update')->name('update');
            Route::delete('/products/{product}', 'destroy')->name('destroy');
            Route::get('/seller/{sellerId}/products', 'sellerProducts')->name('seller-products');
            Route::get('/category/{category}', 'filterByCategory')->name('category');
            Route::post('/search', 'search')->name('search');
        });

    // Products
    Route::controller(ProductController::class)
        ->name('products.')
        ->group(function () {
            Route::post('/products', 'store')->name('store');
            Route::put('/products/{product}', 'update')->name('update');
            Route::delete('/products/{product}', 'destroy')->name('destroy');
            Route::get('/products/{product}', 'show')->name('show');
        });

    // Conversations
    Route::controller(ChatController::class)
        ->name('conversations.')
        ->group(function () {
            Route::post('/conversations', 'startConversation')->name('start');
            Route::get('/conversations', 'index')->name('index');
            Route::get('/conversations/{conversation}', 'show')->name('show');
            Route::post('/conversations/{conversation}/messages', 'sendMessage')->name('messages.store');
        });

    // Chat routes
    Route::controller(ChatController::class)
        ->middleware('auth')
        ->name('chat.')
        ->group(function () {
            Route::get('/chat', 'index')->name('index');
            Route::get('/chat/{conversation}', 'show')->name('show');
            Route::post('/chat/product/{product}', 'store')->name('store');
            Route::post('/chat/{conversation}/messages', 'sendMessage')->name('messages.store');
            Route::get('/contact-admin', 'contactAdmin')->name('contact-admin');
        });

    // Product Rating routes
    Route::post('/products/{product}/rate', [ProductRatingController::class, 'store'])->name('products.rate');

    // Product Reports
    Route::post('/products/report', [ProductReportController::class, 'store'])->name('product.report');
});
