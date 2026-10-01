<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku',
            'is_active' => 'boolean',
        ]);

        $product = Product::create([
    ...$validated,
    'user_id' => $request->user()->id,
]);

        return response()->json($product, 201);
    }

    public function show(Product $product)
    {
        return response()->json($product);
    }

    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    if ($product->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'You are not authorized to update this product.'
        ], 403);
    }

    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'description' => 'sometimes|nullable|string',
        'price' => 'sometimes|required|numeric|min:0',
        'stock' => 'sometimes|required|integer|min:0',
    ]);

    $product->update($validated);

    return response()->json($product);
}

    public function destroy(Request $request, $id)
{
    $product = Product::findOrFail($id);

    if ($product->user_id !== $request->user()->id) {
        return response()->json([
            'message' => 'You are not authorized to delete this product.'
        ], 403);
    }

    $product->delete();

    return response()->json([
        'message' => 'Product deleted successfully'
    ]);
}
}