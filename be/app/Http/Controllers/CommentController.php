<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('per_page', 10); // Mặc định 10 bản ghi

        $comments = Comment::withTrashed()
            ->with('user', 'product')
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('star_rating', 'LIKE', "%{$search}%")
                        ->orWhereHas('user', function ($q) use ($search) {
                            $q->where('username', 'LIKE', "%{$search}%");
                        })
                        ->orWhereHas('product', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%{$search}%");
                        });
                });
            })
            ->orderBy('id', 'desc') // Sắp xếp giảm dần theo cột 'id'
            ->paginate($perPage);
            $noResults = $comments->isEmpty();

        return view('comments.list', compact('comments', 'noResults'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comments = Comment::findOrFail($id); // Lấy tất cả bình luận cùng với user và product liên quan
        return view('comment.show', compact('comments')); // Trả về view kèm dữ liệu bình luận

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.

     */
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
