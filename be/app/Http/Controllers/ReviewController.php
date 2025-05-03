<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    // Danh sách đánh giá
    public function index(Request $request)
    {
        $query = Review::query();

        $search = $request->input('search');

        $reviews = Review::with([
            'user:id,name',
            'order:id',
            'orderDetail.productDetail.product:id,name',
            'orderDetail.productDetail.color:id,name',
            'orderDetail.productDetail.size:id,name'
        ])
            ->when($search, fn($q) =>
            $q->where('content', 'like', "%$search%"))
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();            // giữ tham số search khi chuyển trang
        $reviews = $query->orderBy('id', 'desc')->paginate(5);
        return view('reviews.index', compact('reviews'));
    }

    // Chi tiết một đánh giá
    // app/Http/Controllers/ReviewController.php

    public function show($id)
    {
        $review = Review::with([
            'user:id,name,role',
            'order:id',
            'orderDetail.productDetail.product:id,name,image',   // ← thêm image
            'orderDetail.productDetail.color:id,name',
            'orderDetail.productDetail.size:id,name'
        ])->findOrFail($id);

        return view('reviews.show', compact('review'));
    }
}
