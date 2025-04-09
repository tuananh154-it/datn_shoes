<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Lấy danh sách sản phẩm với phân trang.
     */
    public function index(Request $request)
    {

        $query = Product::where('status', 'active');


        $products = $query->with(['category', 'brand'])->get();
        return response()->json([
            'success' => true,
            'message' => 'Danh sách sản phẩm',
            'data' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    // 'image' => $product->image ? asset('storage/' . $product->image) : null,
                    'image' => $product->image ? $this->convertToBase64($product->image) : null,
                    'price' => number_format($product->price, 2) . ' VND',
                    'description' => $product->description,
                    'status' => $product->status,
                    'category' => $product->category ? $product->category->name : 'Không có',
                    'brand' => $product->brand ? $product->brand->name : 'Không có',
                    'created_at' => $product->created_at ? $product->created_at->format('d-m-Y H:i') : 'Không có dữ liệu',
                    'updated_at' => $product->updated_at ? $product->updated_at->format('d-m-Y H:i') : 'Không có dữ liệu',

                ];
            })
        ]);
    }

    public function show($id)
    {
        $product = Product::where('status', 'active')
            ->with([
                'category',
                'brand',
                'productdetails' => function ($query) {
                    $query->where('status', 'active')->with(['size', 'color']);
                }
            ])
            ->where('id', $id)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy sản phẩm',
                'data' => null
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Chi tiết sản phẩm',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image ? $this->convertToBase64($product->image) : null,
                'price' => number_format($product->price, 2) . ' VND',
                'description' => $product->description ?? 'Không có mô tả',
                'category' => $product->category?->name ?? 'Không có danh mục',
                'brand' => $product->brand?->name ?? 'Không có thương hiệu',
                'details' => $product->productdetails->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'image' => $detail->image ? $this->convertImageArrayToBase64($detail->image) : null,
                        'size' => $detail->size?->name ?? 'Không có size',
                        'color' => $detail->color?->name ?? 'Không có màu',
                        'quantity' => $detail->quantity,
                        'default_price' => number_format($detail->default_price, 2) . ' VND',
                        'discount_price' => $detail->discount_price !== null
                            ? number_format($detail->discount_price, 2) . ' VND'
                            : 'Không giảm giá',
                    ];
                })
            ]
        ]);
    }

    public function latestProducts()
    {
        $products = Product::where('status', 'active')
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->with(['category', 'brand'])
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Danh sách 3 sản phẩm mới nhất',
            'data' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'image' => $product->image ? $this->convertToBase64($product->image) : null,
                    'price' => number_format($product->price, 2) . ' VND',
                    'description' => $product->description ?? 'Không có mô tả',
                    'status' => $product->status,
                    'category' => $product->category?->name ?? 'Không có danh mục',
                    'brand' => $product->brand?->name ?? 'Không có thương hiệu',
                    'created_at' => optional($product->created_at)->format('d-m-Y H:i') ?? 'Không có dữ liệu',
                ];
            })
        ]);
    }

    // Convert 1 ảnh sang base64
    private function convertToBase64($imagePath)
    {
        $fullPath = storage_path('app/public/' . $imagePath);
        if (file_exists($fullPath)) {
            $type = pathinfo($fullPath, PATHINFO_EXTENSION);
            $data = file_get_contents($fullPath);
            return 'data:image/' . $type . ';base64,' . base64_encode($data);
        }

        return null;
    }

    // Convert mảng ảnh json sang mảng base64
    private function convertImageArrayToBase64($images)
    {
        $paths = json_decode($images, true) ?? [];
        return collect($paths)->map(function ($path) {
            return $this->convertToBase64($path);
        })->filter()->values();
    }

}
