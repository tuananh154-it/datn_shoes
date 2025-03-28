<?php

namespace App\Http\Controllers;

use App\Models\ProductDetail;
use App\Models\Product;
use App\Models\Size;
use App\Models\Color;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request, $productId)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
            'default_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:default_price',
            'status' => 'required|in:active,inactive',
        ], [
            'discount_price.lte' => 'Giá giảm không được lớn hơn giá gốc.',
            'default_price.min' => 'Giá gốc phải là một số dương.',
            'discount_price.min' => 'Giá giảm phải là một số dương nếu có.',
        ]);
    
        // ✅ Xử lý ảnh tạm trước
        $imagePaths = [];
        if ($request->hasFile('image')) {
            $imagePaths[] = $request->file('image')->store('tmp_variant_images', 'public');
        } elseif ($request->has('tmp_images')) {
            $imagePaths = $request->input('tmp_images');
        }
    
        // ✅ Nếu validate fail → quay lại + giữ ảnh
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('tmp_variant_images', $imagePaths);
        }
    
        // ✅ Kiểm tra biến thể trùng
        $existingDetail = ProductDetail::where('product_id', $productId)
            ->where('size_id', $request->size_id)
            ->where('color_id', $request->color_id)
            ->first();
    
        if ($existingDetail) {
            return redirect()->back()
                ->withErrors(['message' => 'Biến thể này đã tồn tại!'])
                ->withInput()
                ->with('tmp_variant_images', $imagePaths);
        }
    
        // ✅ Di chuyển ảnh từ tmp sang thư mục thật
        $finalImagePaths = [];
        foreach ($imagePaths as $tmpPath) {
            if (str_starts_with($tmpPath, 'tmp_variant_images/')) {
                $newPath = str_replace('tmp_variant_images/', 'variant_images/', $tmpPath);
                Storage::disk('public')->move($tmpPath, $newPath);
                $finalImagePaths[] = $newPath;
            } else {
                $finalImagePaths[] = $tmpPath;
            }
        }
    
        // ✅ Lưu
        ProductDetail::create([
            'product_id' => $productId,
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'quantity' => $request->quantity,
            'default_price' => $request->default_price,
            'discount_price' => $request->discount_price,
            'status' => $request->status,
            'image' => json_encode($finalImagePaths),
        ]);
    
        return redirect()->route('products.show', ['product' => $productId])
            ->with('success', 'Sản phẩm chi tiết đã được thêm thành công!');
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
            'image.*' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'size_id' => 'required|exists:sizes,id',
            'color_id' => 'required|exists:colors,id',
            'quantity' => 'required|integer|min:1',
            'default_price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lte:default_price',
            'status' => 'required|in:active,inactive',
        ], [
            'discount_price.lte' => 'Giá giảm không được lớn hơn giá gốc.',
            'default_price.min' => 'Giá gốc phải là một số dương.',
            'discount_price.min' => 'Giá giảm phải là một số dương nếu có.',
        ]);
    
        $detail = ProductDetail::findOrFail($id);
        $productId = $detail->product_id;
    
        // ✅ Kiểm tra biến thể trùng (cùng size & color nhưng khác id)
        $duplicate = ProductDetail::where('product_id', $productId)
            ->where('size_id', $request->size_id)
            ->where('color_id', $request->color_id)
            ->where('id', '!=', $id)
            ->first();
    
        if ($duplicate) {
            return redirect()->back()
                ->withErrors(['message' => 'Biến thể với màu sắc và kích thước này đã tồn tại.'])
                ->withInput();
        }
    
        // ✅ Xử lý ảnh mới nếu có
        $imagePaths = [];
    
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($detail->image) {
                $oldImages = json_decode($detail->image, true);
                if (is_array($oldImages)) {
                    foreach ($oldImages as $oldImg) {
                        Storage::disk('public')->delete($oldImg);
                    }
                }
            }
    
            foreach ($request->file('image') as $image) {
                $imagePaths[] = $image->store('variant_images', 'public');
            }
    
            $detail->image = json_encode($imagePaths);
        }
    
        // ✅ Cập nhật chi tiết sản phẩm
        $detail->update([
            'size_id' => $request->size_id,
            'color_id' => $request->color_id,
            'quantity' => $request->quantity,
            'default_price' => $request->default_price,
            'discount_price' => $request->discount_price,
            'status' => $request->status,
            'image' => $detail->image,
        ]);
    
        return redirect()->route('products.show', ['product' => $productId])
            ->with('success', 'Chi tiết sản phẩm đã được cập nhật thành công!');
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
