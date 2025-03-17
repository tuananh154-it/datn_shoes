<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    public function index($product_id = null)
    {
        $query = Comment::with('user')->orderByDesc('created_at');

        if ($product_id) {
            $query->where('product_id', $product_id);
        }

        $comments = $query->get();

        return response()->json($comments->map(function ($comment) {
            return [
                'id' => $comment->id,
                'user_id' => optional($comment->user)->id,
                'user_name' => optional($comment->user)->name ?? 'Unknown',
                'comment' => $comment->comment,
                'star_rating' => $comment->star_rating,
                'file' => $comment->file ? Storage::url($comment->file) : null,
                'created_at' => optional($comment->created_at)->format('Y-m-d H:i:s') ?? 'N/A',

            ];
        }));
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'comment' => 'required_without:file|string',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();
        $product_id = $request->product_id;

        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', 'completed')
            ->whereHas('orderDetails.productVariant', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            })->exists();

        if (!$hasPurchased) {
            return response()->json(['message' => 'Bạn phải mua sản phẩm trước khi bình luận.'], 403);
        }

        $filePath = $request->file('file') ? $request->file('file')->store('comments', 'public') : null;

        $comment = Comment::create([
            'user_id' => $user->id,
            'product_id' => $product_id,
            'comment' => $request->comment,
            'star_rating' => $request->star_rating,
            'file' => $filePath,
        ]);

        return response()->json(['message' => 'Bình luận đã được thêm.', 'comment' => $comment], 201);
    }

    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Bạn không có quyền sửa bình luận này.'], 403);
        }

        if (!$request->hasAny(['comment', 'file', 'star_rating'])) {
            return response()->json(['message' => 'Không có thay đổi nào được thực hiện.'], 400);
        }

        $request->validate([
            'comment' => 'nullable|string',
            'star_rating' => 'nullable|integer|min:1|max:5',
            'file' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($comment->file);
            $comment->file = $request->file('file')->store('comments', 'public');
        }

        $comment->update($request->only(['comment', 'star_rating']));
        return response()->json(['message' => 'Bình luận đã được cập nhật.', 'comment' => $comment]);
    }

    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json(['message' => 'Bạn không có quyền xóa bình luận này.'], 403);
        }

        if ($comment->file) {
            Storage::disk('public')->delete($comment->file);
        }

        $comment->delete();
        return response()->json(['message' => 'Bình luận đã được xóa.']);
    }
    public function show($id)
{
    $comment = Comment::with('user')->find($id);

    if (!$comment) {
        return response()->json(['message' => 'Comment not found'], 404);
    }

    return response()->json([
        'id' => $comment->id,
        'user_id' => optional($comment->user)->id,
        'user_name' => optional($comment->user)->name ?? 'Unknown',
        'comment' => $comment->comment,
        'star_rating' => $comment->star_rating,
        'file' => $comment->file ? Storage::url($comment->file) : null,
        'created_at' => optional($comment->created_at)->format('Y-m-d H:i:s') ?? null,
    ]);
}

}
