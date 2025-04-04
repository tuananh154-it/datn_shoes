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
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'brand_id' => 'required|exists:brands,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'type' => 'required|in:simple,variant',
            'variant' => 'required_if:type,variant|array',
            'variant.*.quantity' => 'required|numeric|min:0',
            'variant.*.default_price' => 'required|numeric|min:0',
            'variant.*.discount_price' => 'nullable|numeric|min:0',
        ], [
            'variant.*.quantity.min' => 'Số lượng phải là số không hoặc lớn hơn.',
            'variant.*.default_price.min' => 'Giá mặc định phải lớn hơn hoặc bằng 0.',
            'variant.*.discount_price.min' => 'Giá giảm phải lớn hơn hoặc bằng 0.',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('tmp_product_images', 'public');
            session()->flash('product_tmp_image', $imagePath);
        } elseif ($request->filled('product_tmp_image')) {
            $imagePath = $request->input('product_tmp_image');
        }

        $product = Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'brand_id' => $request->brand_id,
            'price' => round($request->price, 2),
            'description' => $request->description,
            'status' => $request->status,
            'image' => $imagePath,
        ]);

        if ($request->type == 'variant') {
            $variantTmpImages = [];
            $hasError = false;
            $errors = [];

            foreach ($request->variant as $variantKey => $variantData) {
                $colorId = explode('-', $variantKey)[0];
                $sizeId = explode('-', $variantKey)[1];

                $imagePaths = [];
                if ($request->hasFile("variant_images.{$variantKey}")) {
                    foreach ($request->file("variant_images.{$variantKey}") as $image) {
                        $imagePaths[] = $image->store('tmp_variant_images', 'public');
                    }
                } elseif (isset($request->variant_tmp_images[$variantKey])) {
                    $imagePaths = $request->variant_tmp_images[$variantKey];
                }

                $variantTmpImages[$variantKey] = $imagePaths;

                // Validate giá giảm < giá gốc
                if (isset($variantData['discount_price']) && $variantData['discount_price'] > $variantData['default_price']) {
                    $hasError = true;
                    $errors["variant.{$variantKey}.discount_price"] = 'Giá giảm không được lớn hơn giá gốc.';
                }

                if (!$hasError) {
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

                    ProductDetail::create([
                        'product_id' => $product->id,
                        'color_id' => $colorId,
                        'size_id' => $sizeId,
                        'quantity' => $variantData['quantity'],
                        'default_price' => $variantData['default_price'],
                        'discount_price' => $variantData['discount_price'] ?? null,
                        'image' => json_encode($finalImagePaths),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            if ($hasError) {
                $product->delete();
                return redirect()->back()
                    ->withErrors($errors)
                    ->withInput()
                    ->with('variant_tmp_images', $variantTmpImages);
            }
        }

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

    public function destroy(string $id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('products.index')->with('success', 'Sản phẩm đã bị xóa thành công!');
    }
}
