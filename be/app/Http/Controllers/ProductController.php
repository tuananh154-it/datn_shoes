<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $status = $request->input('status');
    
        $query = Product::query();
    
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
    
        if ($status) {
            $query->where('status', $status);
        }
    
        $products = $query->orderBy('id', 'desc')->paginate(5);
    
        return view('blocks.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = Size::all();  
        $colors = Color::all();  

        return view('blocks.products.create', compact('categories', 'brands', 'sizes', 'colors'));
    }
    public function show(string $id)
    {
       
        $product = Product::with('category', 'brand')->find($id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Sản phẩm không tồn tại.');
        }

        return view('blocks.products.show', compact('product'));
    }
    /**
     * Store a newly created resource in storage.
     */public function store(Request $request)
{
    // Validate dữ liệu từ form
    // dd($request->all());

    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'price' => 'required|numeric',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'type' => 'required|in:simple,variant',
        'variant' => 'required_if:type,variant|array', // Kiểm tra trường 'variant' khi 'type' là 'variant'
        'variant.*.quantity' => 'required|numeric',
        'variant.*.default_price' => 'required|numeric',
        'variant.*.discount_price' => 'nullable|numeric',
        'variant.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    ]);

    // Lưu sản phẩm chính
   // Xử lý ảnh sản phẩm chính
   $imagePath = null;
   if ($request->hasFile('image')) {
       $imagePath = $request->file('image')->store('product_images', 'public');
   }

   // Tạo sản phẩm mới
   $product = Product::create([
       'name' => $request->name,
       'category_id' => $request->category_id,
       'brand_id' => $request->brand_id,
       'price' => round($request->price, 2),
       'description' => $request->description,
       'status' => $request->status,
       'image' => $imagePath,
   ]);

    // Nếu là sản phẩm có biến thể
    if ($request->type == 'variant') {
        foreach ($request->variant as $variantKey => $variantData) {
            $colorId = explode('-', $variantKey)[0];
            $sizeId = explode('-', $variantKey)[1];

            // Xử lý ảnh biến thể
            $imagePaths = [];
            if ($request->hasFile("variant_images.{$colorId}-{$sizeId}")) {
                foreach ($request->file("variant_images.{$colorId}-{$sizeId}") as $image) {
                    $imagePaths[] = $image->store('variant_images', 'public');
                }
            }
            
            // Lưu thông tin biến thể
            ProductDetail::create([
                'product_id' => $product->id,
                'color_id' => $colorId,
                'size_id' => $sizeId,
                'quantity' => $variantData['quantity'],
                'default_price' => $variantData['default_price'],
                'discount_price' => $variantData['discount_price'] ?? null,
                'image' => json_encode($imagePaths), // Lưu danh sách ảnh dưới dạng JSON
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
}

    
    

    /**
     * Edit the specified resource in storage.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = Size::all();
        $colors = Color::all();
        $product = Product::findOrFail($id);
        return view('blocks.products.edit', compact('product', 'categories', 'brands', 'sizes', 'colors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);

        $product = Product::findOrFail($id);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã bị xóa thành công!');
    }
}
