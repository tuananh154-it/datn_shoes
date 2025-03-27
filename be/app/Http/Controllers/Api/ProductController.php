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
                    'image' => $product->image ? asset('storage/' . $product->image) : null,
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
            ->first(); // Thay vì paginate(3)


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
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'price' => number_format($product->price, 2) . ' VND',
                'description' => $product->description ?? 'Không có mô tả',
                'category' => $product->category ? $product->category->name : 'Không có danh mục',
                'brand' => $product->brand ? $product->brand->name : 'Không có thương hiệu',
                'details' => $product->productdetails->map(function ($detail) {
                    return [
                        'id' => $detail->id,
                        'image' => $detail->image ? asset('storage/' . $detail->image) : null,
                        'size' => $detail->size ? $detail->size->name : 'Không có size',
                        'color' => $detail->color ? $detail->color->name : 'Không có màu',
                        'quantity' => $detail->quantity,
                        'default_price' => number_format($detail->default_price, 2) . ' VND',
                        'discount_price' => $detail->discount_price !== null ? number_format($detail->discount_price, 2) . ' VND' : 'Không giảm giá'
                    ];
                })
            ]
        ]);
    }
    public function latestProducts()
{
    $products = Product::where('status', 'active')
        ->orderBy('created_at', 'desc') // Sắp xếp theo ngày tạo giảm dần
        ->take(3) // Lấy 3 sản phẩm mới nhất
        ->with(['category', 'brand']) // Lấy thêm thông tin danh mục và thương hiệu
        ->get();

    return response()->json([
        'success' => true,
        'message' => 'Danh sách 3 sản phẩm mới nhất',
        'data' => $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'image' => $product->image ? asset('storage/' . $product->image) : null,
                'price' => number_format($product->price, 2) . ' VND',
                'description' => $product->description ?? 'Không có mô tả',
                'status' => $product->status,
                'category' => $product->category ? $product->category->name : 'Không có danh mục',
                'brand' => $product->brand ? $product->brand->name : 'Không có thương hiệu',
                'created_at' => $product->created_at ? $product->created_at->format('d-m-Y H:i') : 'Không có dữ liệu',
            ];
        })
    ]);
}

}
