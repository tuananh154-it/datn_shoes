<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi

        $query = Comment::query()
            ->withTrashed()
            ->with('user', 'product')
            ->orderBy('id', 'desc'); // Sắp xếp giảm dần theo cột 'id'

        // Thêm logic tìm kiếm theo tên sản phẩm
        if ($searchTerm) {
            $query->whereHas('product', function ($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%");
            });
        }

        $comments = $query->paginate($perPage);
        $noResults = $comments->isEmpty();

        \Log::info('Search keyword:', ['search' => $searchTerm]);
        \Log::info('Found comments:', ['count' => $comments->count(), 'comments' => $comments->toArray()]);

        return view('comments.list', compact('comments', 'noResults'));
    }

    public function show(string $id)
    {
        $comment = Comment::findOrFail($id); // Lấy bình luận theo ID
        return view('comments.show', compact('comment'));
    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        $comment->delete();
        return redirect()->route('comments.index')->with('success', 'Bình luận đã được xóa thành công (xóa mềm)!');
    }

    public function restore($id)
    {
        $comment = Comment::withTrashed()->findOrFail($id);
        $comment->restore();
        return redirect()->route('comments.index')->with('success', 'Bình luận đã được khôi phục thành công!');
    }
}