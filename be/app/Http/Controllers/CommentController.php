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
        $query = Comment::query();

        $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi

        $comments = Comment::withTrashed()
            ->with('user', 'product')
            ->orderBy('id', 'desc') // Sắp xếp giảm dần theo cột 'id'
            ->paginate($perPage);
            $noResults = $comments->isEmpty();

        return view('comments.list', compact('comments', 'noResults'));
    }

    public function show(string $id)
    {
        $comments = Comment::findOrFail($id); // Lấy tất cả bình luận cùng với user và product liên quan
        return view('comments.show', compact('comments')); // Trả về view kèm dữ liệu bình luận

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
