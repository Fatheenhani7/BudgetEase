<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRating;
use Illuminate\Http\Request;

class ProductRatingController extends Controller
{
    public function store(Request $request, Product $product)
    {
        // Validate the request
        $validatedData = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:500',
        ]);

        // Check if user has already rated this product
        $existingRating = ProductRating::where('user_id', auth()->id())
            ->where('product_id', $product->id)
            ->first();

        if ($existingRating) {
            return back()->with('error', 'You have already rated this product.');
        }

        // Check if user is the seller
        if ($product->seller_id === auth()->id()) {
            return back()->with('error', 'You cannot rate your own product.');
        }

        // Create the rating
        ProductRating::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'rating' => $validatedData['rating'],
            'review' => $validatedData['review'],
        ]);

        return back()->with('success', 'Thank you for your rating!');
    }
}
