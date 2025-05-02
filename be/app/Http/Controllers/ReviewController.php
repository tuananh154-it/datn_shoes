<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Danh sách đánh giá
    public function index(Request $request)
    {
        $search       = $request->input('search');      // nội dung đánh giá
        $rating       = $request->input('rating');      // số sao (1-5…)
        $productName  = $request->input('product');     // tên sản phẩm

        $reviews = Review::with([
            'user:id,name',
            'order:id',
            'orderDetail.productDetail.product:id,name',
            'orderDetail.productDetail.color:id,name',
            'orderDetail.productDetail.size:id,name'
        ])
            // lọc theo nội dung
            ->when($search, function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%");
            })
            // lọc theo rating
            ->when($rating, function ($q) use ($rating) {
                $q->where('rating', $rating);
            })
            // lọc theo tên sản phẩm
            ->when($productName, function ($q) use ($productName) {
                $q->whereHas('orderDetail.productDetail.product', function ($p) use ($productName) {
                    $p->where('name', 'like', "%{$productName}%");
                });
            })
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();  // giữ tham số khi chuyển trang

        return view('reviews.index', compact('reviews'));
    }

    // Chi tiết một đánh giá
    public function show($id)
    {
        $review = Review::with([
            'user:id,name,role',
            'order:id',
            'orderDetail.productDetail.product:id,name,image',
            'orderDetail.productDetail.color:id,name',
            'orderDetail.productDetail.size:id,name'
        ])->findOrFail($id);

        return view('reviews.show', compact('review'));
    }
}