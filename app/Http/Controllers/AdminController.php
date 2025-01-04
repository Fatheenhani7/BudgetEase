<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        if (!Auth::check() || Auth::user()->email !== 'adminb@gmail.com') {
            return redirect('/home')->with('error', 'Unauthorized access. Admin privileges required.');
        }

        $query = Product::with(['seller', 'images'])->latest();
        
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }
        
        $products = $query->paginate(10);

        $usersQuery = UserInfo::latest();
        
        if ($request->has('user_search')) {
            $userSearch = $request->get('user_search');
            $usersQuery->where(function($q) use ($userSearch) {
                $q->where('username', 'like', "%{$userSearch}%")
                  ->orWhere('email', 'like', "%{$userSearch}%");
            });
        }
        
        $users = $usersQuery->paginate(10);
        
        return view('admin.index', compact('products', 'users'));
    }

    public function deleteProduct($id)
    {
        if (!Auth::check() || Auth::user()->email !== 'adminb@gmail.com') {
            return redirect('/home')->with('error', 'Unauthorized access. Admin privileges required.');
        }

        $product = Product::with('images')->findOrFail($id);
        
        // Delete associated images from storage
        foreach($product->images as $image) {
            if($image->image_url && Storage::exists('public/' . $image->image_url)) {
                Storage::delete('public/' . $image->image_url);
            }
        }
        
        $product->delete();
        return redirect()->route('admin.index')->with('success', 'Product deleted successfully');
    }

    public function deleteUser($id)
    {
        if (!Auth::check() || Auth::user()->email !== 'adminb@gmail.com') {
            return redirect('/home')->with('error', 'Unauthorized access. Admin privileges required.');
        }

        $user = UserInfo::findOrFail($id);
        
        // Don't allow deleting the admin
        if($user->email === 'adminb@gmail.com') {
            return redirect()->route('admin.index')->with('error', 'Cannot delete admin user');
        }
        
        // Delete user's products and their images
        foreach($user->products as $product) {
            foreach($product->images as $image) {
                if($image->image_url && Storage::exists('public/' . $image->image_url)) {
                    Storage::delete('public/' . $image->image_url);
                }
            }
            $product->delete();
        }
        
        $user->delete();
        return redirect()->route('admin.index')->with('success', 'User and all their products deleted successfully');
    }

    public function viewUser($id)
    {
        if (!Auth::check() || Auth::user()->email !== 'adminb@gmail.com') {
            return redirect('/home')->with('error', 'Unauthorized access. Admin privileges required.');
        }

        $user = UserInfo::with(['products' => function($query) {
            $query->with('images')->latest();
        }, 'budgets', 'expenses', 'incomes'])->findOrFail($id);

        // Calculate total products
        $totalProducts = $user->products->count();

        // Calculate total expenses
        $totalExpenses = $user->expenses->sum('amount');

        // Calculate total income
        $totalIncome = $user->incomes->sum('amount');

        // Calculate total budgets
        $totalBudgets = $user->budgets->count();

        return view('admin.user-profile', compact('user', 'totalProducts', 'totalExpenses', 'totalIncome', 'totalBudgets'));
    }
}
