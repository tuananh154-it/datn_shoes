<?php

namespace App\Http\Controllers;

use App\Models\{Brand, Category, Color, Product, ProductDetail, Size};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, Storage};

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:create-product', ['only' => ['create', 'store']]);
        $this->middleware('permission:edit-product', ['only' => ['edit', 'update']]);
        $this->middleware('permission:show-products', ['only' => ['index', 'show']]);
    }

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
     */
    public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            'unique:products,name',
        ],
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'price' => 'required|numeric|min:0',
        'description' => 'nullable|string|max:5000',
        'status' => 'required|in:active,inactive',
        'image' => 'required_if:type,simple|nullable|image',
        'type' => 'required|in:simple,variant',
        'variant' => 'required_if:type,variant|array',
        'variant.*.quantity' => 'required|numeric|min:0',
        'variant.*.default_price' => 'required|numeric|min:0',
        'variant.*.discount_price' => 'nullable|numeric|min:0|lte:variant.*.default_price',
    ], [
        'name.unique' => 'Tên sản phẩm đã tồn tại.',
        'image.required_if' => 'Ảnh sản phẩm là bắt buộc nếu là sản phẩm đơn.',
        'variant.*.discount_price.lte' => 'Giá giảm không được lớn hơn giá gốc.',
    ]);

    // 👉 Xử lý ảnh đơn sản phẩm
    $imagePath = null;
    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('tmp_product_images', 'public');
    } elseif ($request->filled('product_tmp_image')) {
        $imagePath = $request->input('product_tmp_image');
    }

    // ⚠️ Bắt buộc phải flash lại ảnh tạm dù từ input hay file mới
    session()->flash('product_tmp_image', $imagePath);

    // Nếu là sản phẩm đơn nhưng không có ảnh, trả lỗi
    if ($request->type === 'simple' && !$imagePath) {
        return redirect()->back()
            ->withErrors(['image' => 'Ảnh sản phẩm là bắt buộc.'])
            ->withInput()
            ->with('product_tmp_image', $imagePath);
    }

    // 👉 Tạo mới sản phẩm
    $product = Product::create([
        'name' => $request->name,
        'category_id' => $request->category_id,
        'brand_id' => $request->brand_id,
        'price' => round($request->price, 2),
        'description' => $request->description,
        'status' => $request->status,
        'image' => $imagePath,
    ]);

    // 👉 Nếu là sản phẩm biến thể
    if ($request->type === 'variant') {
        $variantTmpImages = [];
        $hasError = false;
        $errors = [];

        foreach ($request->variant as $variantKey => $variantData) {
            $colorId = explode('-', $variantKey)[0];
            $sizeId = explode('-', $variantKey)[1];

            $imagePaths = [];

            if ($request->hasFile("variant_images.{$variantKey}")) {
                foreach ($request->file("variant_images.{$variantKey}") as $img) {
                    $imagePaths[] = $img->store('tmp_variant_images', 'public');
                }
            } elseif (isset($request->variant_tmp_images[$variantKey])) {
                $imagePaths = $request->variant_tmp_images[$variantKey];
            }

            $variantTmpImages[$variantKey] = $imagePaths;

            // ❌ Check giá giảm > giá gốc
            if (isset($variantData['discount_price']) && $variantData['discount_price'] > $variantData['default_price']) {
                $hasError = true;
                $errors["variant.{$variantKey}.discount_price"] = 'Giá giảm không được lớn hơn giá gốc.';
            }

            if (!$hasError) {
                $finalPaths = [];
                foreach ($imagePaths as $tmp) {
                    if (str_starts_with($tmp, 'tmp_variant_images/')) {
                        $newPath = str_replace('tmp_variant_images/', 'variant_images/', $tmp);
                        Storage::disk('public')->move($tmp, $newPath);
                        $finalPaths[] = $newPath;
                    } else {
                        $finalPaths[] = $tmp;
                    }
                }

                ProductDetail::create([
                    'product_id' => $product->id,
                    'color_id' => $colorId,
                    'size_id' => $sizeId,
                    'quantity' => $variantData['quantity'],
                    'default_price' => $variantData['default_price'],
                    'discount_price' => $variantData['discount_price'] ?? null,
                    'image' => json_encode($finalPaths),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        if ($hasError) {
            $product->delete(); // rollback nếu lỗi
            session()->flash('product_tmp_image', $imagePath); // giữ lại ảnh chính
            return redirect()->back()
                ->withErrors($errors)
                ->withInput()
                ->with('variant_tmp_images', $variantTmpImages);
        }
    }

    // ✅ Thành công
    return redirect()->route('products.index')->with('success', 'Sản phẩm đã được thêm thành công!');
}


    public function edit(string $id)
    {
        $categories = Category::all();
        $brands = Brand::all();
        $sizes = Size::all();
        $colors = Color::all();
        $product = Product::findOrFail($id);
        return view('blocks.products.edit', compact('product', 'categories', 'brands', 'sizes', 'colors'));
    }

    public function update(Request $request, string $id)
{
    $request->validate([
        'name' => 'required|string|max:255|unique:products,name,' . $id,
        'price' => 'required|numeric',
        'description' => 'nullable|string',
        'status' => 'required|in:active,inactive',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'required|exists:brands,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // validate thêm ảnh nếu có
    ],
    [
        'name.unique' => 'Tên sản phẩm đã tồn tại. Vui lòng chọn tên khác.'
    ]);
    

    $product = Product::findOrFail($id);

    // Xử lý ảnh nếu có upload ảnh mới
    if ($request->hasFile('image')) {
        // Xoá ảnh cũ nếu tồn tại
        if ($product->image && Storage::exists('public/' . $product->image)) {
            Storage::delete('public/' . $product->image);
        }

        // Lưu ảnh mới
        $path = $request->file('image')->store('products', 'public');
        $product->image = $path;
    }

    // Cập nhật thông tin sản phẩm
    $product->update([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
        'status' => $request->status,
        'category_id' => $request->category_id,
        'brand_id' => $request->brand_id,
        'image' => $product->image, // cập nhật ảnh nếu có
    ]);

    return redirect()->route('products.index')->with('success', 'Sản phẩm đã được cập nhật thành công!');
}


    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã bị xóa thành công!');
    }
}