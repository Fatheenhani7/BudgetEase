<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\Product;
use App\Models\User;
use App\Models\UserProfile;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $user->load('profile'); // Eager load the profile relationship
        return view('pages.profile', ['user' => $user]);
    }

    public function index()
    {
        $user = Auth::user();
        $products = Product::where('seller_id', $user->id)
                         ->with(['images'])
                         ->latest()
                         ->get();

        return view('pages.profile', compact('user', 'products'));
    }

    public function edit()
    {
        $user = Auth::user();
        $user->load('profile'); // Eager load the profile relationship
        return view('pages.edit-profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('users_info')->ignore($user->id)],
            'phone_number' => ['nullable', 'string', 'max:15'],
            'bio' => ['nullable', 'string', 'max:500'],
            'profile_picture' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // max 2MB
            'current_password' => ['nullable', 'required_with:password', function ($attribute, $value, $fail) use ($user) {
                if (!Hash::check($value, $user->password)) {
                    $fail('The current password is incorrect.');
                }
            }],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // Update username
        $user->username = $validated['username'];
        
        // Update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        // Update or create profile details
        $profile = $user->profile ?? UserProfile::create(['user_id' => $user->id]);

        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if it exists
            if ($profile->profile_picture_url) {
                Storage::disk('public')->delete($profile->profile_picture_url);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $profile->profile_picture_url = $path;
        }

        $profile->phone_number = $validated['phone_number'];
        $profile->bio = $validated['bio'];
        $profile->save();

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully.');
    }

    public function editProduct($id)
    {
        $product = Product::where('seller_id', Auth::id())
                         ->where('id', $id)
                         ->firstOrFail();

        $categories = \App\Models\MarketplaceCategory::all();
        
        return view('pages.edit-product', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = Product::where('seller_id', Auth::id())
                         ->where('id', $id)
                         ->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|exists:marketplace_categories,name',
            'location' => 'required|string',
            'images.*' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'location' => $request->location
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('product-images', 'public');
                
                $product->images()->create([
                    'image_url' => $path,
                    'is_primary' => $index === 0
                ]);
            }
        }

        return redirect()->route('profile.index')->with('success', 'Product updated successfully!');
    }

    public function deleteProduct($id)
    {
        $product = Product::where('seller_id', Auth::id())
                         ->where('id', $id)
                         ->firstOrFail();

        // Delete associated images from storage
        foreach ($product->images as $image) {
            \Storage::disk('public')->delete($image->image_url);
        }

        $product->delete();

        return redirect()->route('profile.index')->with('success', 'Product deleted successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'Password updated successfully.');
    }
}
