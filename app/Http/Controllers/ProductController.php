<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|exists:marketplace_categories,name',
            'condition_status' => 'required|string|max:20',
            'location' => 'required|string|max:255'
        ]);

        $validatedData['seller_id'] = auth()->user()->id;
        Product::create($validatedData);

        return redirect()->back();
    }

    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|exists:marketplace_categories,name',
            'condition_status' => 'required|string|max:20',
            'location' => 'required|string|max:255'
        ]);

        $product->update($validatedData);

        return redirect()->back();
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->back();
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
