<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use Illuminate\Http\Request;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(ProductDetail::with('product', 'size', 'color')->get(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:product,id',
            'size_id' => 'required|integer|exists:size,id',
            'color_id' => 'required|integer|exists:color,id',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'status' => 'string|nullable'
        ]);

        $productDetail = ProductDetail::create($request->all());
        return response()->json($productDetail->load('product', 'size', 'color'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $productDetail = ProductDetail::findOrFail($id);
        $productDetail->update($request->all());
        return response()->json($productDetail->load('product', 'size', 'color'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $productDetail = ProductDetail::findOrFail($id);
        $productDetail->delete();
        return response()->json(['message' => 'Product Detail deleted'], 200);
    }
}
