<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
        // $searchTerm = $request->input('search');
        // $category = $request->input('category_id');
        // $brand = $request->input('brand_id');
    
        $query = Product::where('status', 'active');
    
        // if ($searchTerm) {
        //     $query->where('name', 'like', "%{$searchTerm}%");
        // }
        // if ($category) {
        //     $query->where('category_id', $category);
        // }
        // if ($brand) {
        //     $query->where('brand_id', $brand);
        // }
    
        // Lấy tất cả sản phẩm
        $products = $query->with(['category', 'brand'])->get();
    
        // if ($products->isEmpty()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Không có sản phẩm nào ',
        //         'data' => []
        //     ], 200);
        // }
    
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
                    'created_at' => $product->created_at->format('d-m-Y H:i'),
                    'updated_at' => $product->updated_at->format('d-m-Y H:i'),
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
            ])->where('id', $id)->first();
    
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
    
    
}


