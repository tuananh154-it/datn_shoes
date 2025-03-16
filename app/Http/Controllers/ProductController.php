<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductDetail;
use App\Models\Size;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Lấy các tham số tìm kiếm từ form
        $searchTerm = $request->input('search');
        $status = $request->input('status');
    
        $query = Product::query();
    
        // Tìm kiếm theo tên nếu có
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
    
        // Tìm kiếm theo trạng thái nếu có
        if ($status) {
            $query->where('status', $status);
        }
    
        
        // Lấy sản phẩm sau khi lọc và phân trang
        $products = $query->orderBy('id', 'desc')->paginate(5);
    
        return view('blocks.products.index', compact('products'));
    }
    



    public function create()
    {
        $categories = Category::all();
        $brands = Brand::all();
        return view('blocks.products.create', compact('categories', 'brands'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:products,name',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);


        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('product_images', 'public');
        }

        // Tạo sản phẩm mới
        $product = Product::create([
            'name' => $request->name,
            'image' => $imagePath, 
            'price' => $request->price,
            'description' => $request->description,
            'status' => $request->status,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
        ]);

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã được tạo thành công!');
    }


    /**
     * Display the specified resource.
     */
   


    /**
     * Update the specified resource in storage.
     */
    public function edit(string $id)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $product = Product::findOrFail($id);
        return view('blocks.products.edit', compact('product', 'categories', 'brands'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
       
        $product = Product::with('category', 'brand')->find($id);

        if (!$product) {
            return redirect()->route('products.index')->with('error', 'Sản phẩm không tồn tại.');
        }

        return view('blocks.products.show', compact('product'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,png,jpeg,gif',
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
        ]);


        $product = Product::findOrFail($id);

        $imagePath = $product->image;

        if ($request->hasFile('image')) {

            if ($product->image && file_exists(storage_path('app/public/' . $product->image))) {
                unlink(storage_path('app/public/' . $product->image));
            }

            $imagePath = $request->file('image')->store('product_images', 'public');
        }


        $product->update([
            'name' => $request->name,
            'image' => $imagePath,
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

        return redirect()->route('products.index')->with('success', 'Product deleted successfully!');
    }
}