<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function create($productId)
    {
        $product = Product::findOrFail($productId);
        $sizes = Size::all();
        $colors = Color::all();
        return view('blocks.productdts.create', compact('product', 'sizes', 'colors'));
    }

    /**
     * Lưu chi tiết sản phẩm.
     */
    public function store(Request $request, $productId)
    {
        // Validate input
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
            'default_price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        // Xử lý hình ảnh nếu có
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_details_images', 'public');
        }

        // Tạo sản phẩm chi tiết
        ProductDetail::create([
            'product_id' => $productId,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'quantity' => $request->quantity,
            'default_price' => $request->default_price,
            'discount_price' => $request->discount_price,
            'status' => $request->status,
            'image' => $imagePath,
        ]);
        return redirect()->route('products.show', ['product' => $productId])->with('success', 'Sản phẩm chi tiết đã được thêm thành công!');


    }

    public function edit($id)
    {
        $detail = ProductDetail::findOrFail($id);
        $sizes = Size::all();
        $colors = Color::all();
        return view('blocks.productdts.edit', compact('detail', 'sizes', 'colors'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
            'default_price' => 'required|numeric',
            'discount_price' => 'nullable|numeric',
            'status' => 'required|in:active,inactive',
        ]);

        $detail = ProductDetail::findOrFail($id);
        $productId = $detail->product_id;

        // Cập nhật hình ảnh nếu có
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($detail->image) {
                Storage::disk('public')->delete($detail->image);
            }
            $detail->image = $request->file('image')->store('product_details_images', 'public');
        }

        // Cập nhật thông tin sản phẩm chi tiết
        $detail->update([
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'quantity' => $request->quantity,
            'default_price' => $request->default_price,
            'discount_price' => $request->discount_price,
            'status' => $request->status,
        ]);

        return redirect()->route('products.show', ['product' => $productId])->with('success', 'Chi tiết sản phẩm đã được cập nhật thành công!');
    }
    public function destroy($id)
    {
        $detail = ProductDetail::findOrFail($id);
        $productId = $detail->product_id;
        
        // Xóa mềm
        $detail->delete();

        return redirect()->route('products.show', ['product' => $productId])->with('success', 'Chi tiết sản phẩm đã được xóa!');
    }
  
}
