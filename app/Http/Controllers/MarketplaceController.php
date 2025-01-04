<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MarketplaceCategory;
use App\Models\ProductImage;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['seller', 'images']);

        // Apply category filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Apply location filter only if a specific location is selected
        if ($request->has('location') && $request->location !== '' && $request->location !== 'all') {
            $query->where('location', $request->location);
        }

<<<<<<< HEAD
        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('category', 'like', "%{$search}%");
            });
        }

=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }

<<<<<<< HEAD
        $products = $query->paginate(12)->withQueryString();
=======
        $products = $query->paginate(12);
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
        $categories = MarketplaceCategory::all();

        return view('pages.marketplace', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = MarketplaceCategory::all();
        return view('pages.create-product', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|exists:marketplace_categories,name',
<<<<<<< HEAD
            'condition_status' => 'required|string|max:20',
=======
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
            'location' => 'required|string',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = new Product([
            'seller_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
<<<<<<< HEAD
            'condition_status' => $request->condition_status,
=======
            'condition_status' => 'Used',
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
            'location' => $request->location
        ]);

        $product->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('product-images', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                    'is_primary' => $index === 0
                ]);
            }
        }

        return redirect()->route('marketplace.show', $product->id)
            ->with('success', 'Product listed successfully!');
    }

    public function show(Product $product)
    {
<<<<<<< HEAD
        // Get similar products in the same category, excluding the current product
        $similarProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->with(['seller', 'images'])
            ->inRandomOrder()
            ->take(4)
            ->get();

        return view('pages.marketplace.show', [
            'product' => $product->load(['seller', 'images']),
            'similarProducts' => $similarProducts
        ]);
=======
        $product->load(['seller', 'images']);
        return view('pages.product-details', compact('product'));
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
    }

    public function edit(Product $product)
    {
        // Check if user is the owner of the product
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('marketplace.show', $product->id)
                ->with('error', 'You are not authorized to edit this product.');
        }

        $categories = MarketplaceCategory::all();
        return view('pages.edit-product', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Check if user is the owner of the product
        if ($product->seller_id !== Auth::id()) {
            return redirect()->route('marketplace.show', $product->id)
                ->with('error', 'You are not authorized to edit this product.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|exists:marketplace_categories,name',
            'location' => 'required|string',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product->update([
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'location' => $request->location
        ]);

        // Handle new images if uploaded
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_url);
                $image->delete();
            }

            // Store new images
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('product-images', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $path,
                    'is_primary' => $index === 0
                ]);
            }
        }

        return redirect()->route('marketplace.show', $product->id)
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        // Check if the authenticated user owns this product
        if ($product->seller_id !== Auth::id()) {
            return redirect()->back()->with('error', 'You are not authorized to delete this product.');
        }

        // Delete all associated images from storage
        foreach ($product->images as $image) {
            Storage::disk('public')->delete($image->image_url);
        }

        // Delete the product (this will cascade delete related records)
        $product->delete();

        return redirect()->route('profile.index')->with('success', 'Product deleted successfully.');
    }

<<<<<<< HEAD
    public function sellerProducts($sellerId)
    {
        $seller = UserInfo::findOrFail($sellerId);
        $products = Product::where('seller_id', $sellerId)
            ->with(['images'])
=======
    public function sellerProducts(UserInfo $seller)
    {
        $products = Product::where('seller_id', $seller->id)
            ->with(['images', 'seller'])
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
            ->latest()
            ->paginate(12);

        return view('pages.marketplace.seller-products', [
<<<<<<< HEAD
            'seller' => $seller,
            'products' => $products
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('query');
        $products = Product::where('title', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ->with(['seller', 'images'])
            ->latest()
            ->paginate(12);

        return view('pages.marketplace', [
            'products' => $products,
            'categories' => MarketplaceCategory::all()
        ]);
    }

    public function filterByCategory($category)
    {
        $products = Product::where('category', $category)
            ->with(['seller', 'images'])
            ->latest()
            ->paginate(12);

        $categories = MarketplaceCategory::all();

        return view('pages.marketplace', compact('products', 'categories'));
    }
=======
            'products' => $products,
            'seller' => $seller
        ]);
    }
>>>>>>> 9f54a7f70537ac620d030b65705c3379f4ec70bb
}
