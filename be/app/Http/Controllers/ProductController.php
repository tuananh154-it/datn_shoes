<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with('details.size', 'details.color')->get();
        return response()->json($products, 200);
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer',
            'brand_id' => 'required|integer',
            'price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'image_url' => 'nullable|string',
            'status' => 'string|nullable',
            'variants' => 'array'
        ]);

        $product = Product::create($request->only(['name', 'description', 'category_id', 'brand_id', 'price', 'discount_price', 'image_url', 'status']));

        if ($request->has('variants')) {
            foreach ($request->variants as $variant) {
                ProductDetail::create([
                    'product_id' => $product->id,
                    'size_id' => $variant['size_id'],
                    'color_id' => $variant['color_id'],
                    'price' => $variant['price'],
                    'quantity' => $variant['quantity'],
                    'status' => $variant['status']
                ]);
            }
        }

        return response()->json($product->load('details.size', 'details.color'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::with('details.size', 'details.color')->find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::findOrFail($id);
        $product->update($request->only(['name', 'description', 'category_id', 'brand_id', 'price', 'discount_price', 'image_url', 'status']));
        return response()->json($product, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted'], 200);
    }
}
