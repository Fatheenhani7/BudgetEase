<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use App\Models\UserInfo;
use App\Models\Product;
use App\Models\ProductReport;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $user = Auth::user();
        $products = $user->products()->with('images')->latest()->get();
        $conversations = $user->conversations()
            ->with(['product.images', 'lastMessage', 'buyer', 'seller'])
            ->latest()
            ->get();
        
        // Get reported products for the user
        $reportedProducts = ProductReport::whereHas('product', function($query) use ($user) {
            $query->where('seller_id', $user->id);
        })->with('product')->latest()->get();
        
        // Debug information
        Log::info('User ID: ' . $user->id);
        Log::info('Conversations count: ' . $conversations->count());
        foreach ($conversations as $conversation) {
            Log::info('Conversation ID: ' . $conversation->id);
            if ($conversation->product) {
                Log::info('Product: ' . $conversation->product->title);
            } else {
                Log::info('Admin Support Chat');
            }
            Log::info('Buyer: ' . $conversation->buyer->username);
            Log::info('Seller: ' . $conversation->seller->username);
        }
        
        return view('pages.profile', compact('user', 'products', 'conversations', 'reportedProducts'));
    }

    public function edit()
    {
        $user = Auth::user();
        return view('pages.edit-profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'username' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->update([
            'username' => $request->username,
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            
            // Create or update user profile
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'profile_picture_url' => $path,
                    'phone_number' => $request->phone_number,
                    'bio' => $request->bio,
                ]
            );
        } else {
            // Update profile without changing picture
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'phone_number' => $request->phone_number,
                    'bio' => $request->bio,
                ]
            );
        }

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully');
    }

    public function deleteProduct(Product $product)
    {
        // Check if the user owns this product
        if ($product->seller_id !== auth()->id()) {
            return back()->with('error', 'You are not authorized to delete this product.');
        }

        // Delete product images from storage
        foreach($product->images as $image) {
            if($image->image_url && Storage::exists('public/' . $image->image_url)) {
                Storage::delete('public/' . $image->image_url);
            }
        }
        
        // Delete the product
        $product->delete();

        return back()->with('success', 'Product deleted successfully.');
    }
}
