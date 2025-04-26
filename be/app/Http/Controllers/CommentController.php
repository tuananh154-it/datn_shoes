<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Hiển thị danh sách bình luận kèm người dùng & sản phẩm.
     */
    public function index(Request $request)
    {
        $perPage     = $request->input('per_page', 10);        // Mặc định 10 bản ghi
        $searchTerm  = $request->input('search');

        $query = Comment::with(['user', 'product'])
            ->orderByDesc('id');

        if ($searchTerm) {
            $query->where('content', 'like', "%{$searchTerm}%");
        }

        $comments  = $query->paginate($perPage);
        $noResults = $comments->isEmpty();

        return view('comments.list', compact('comments', 'noResults'));
    }

    /**
     * Hiển thị chi tiết một bình luận.
     */
    public function show(string $id)
    {
        $comment = Comment::with(['user', 'product'])->findOrFail($id);
        return view('comments.show', compact('comment'));
    }
}
