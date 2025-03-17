<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticlesController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');

        $query = Article::query();

        // Tìm kiếm theo tên nếu có
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        // Sắp xếp giảm dần theo cột 'id'
        $articles = $query->orderBy('id', 'desc')->paginate(10); // Hoặc dùng ->get() nếu không cần phân trang

        $noResults = $articles->isEmpty(); // Kiểm tra nếu không có kết quả tìm kiếm

        return view('articles.index', compact('articles', 'noResults'));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('articles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Xác thực dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'name.required' => 'Vui lòng nhập tên.',
            'name.string' => 'Tên phải là chuỗi ký tự.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',

            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',

            'content.required' => 'Vui lòng nhập nội dung.',
            'content.string' => 'Nội dung phải là chuỗi ký tự.',

            'image.image' => 'File phải là một hình ảnh.',
            'image.mimes' => 'Ảnh chỉ cho phép các định dạng jpeg, png, jpg, gif, svg.',
            'image.max' => 'Ảnh không được vượt quá 2MB.'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image')->store('uploads/article', 'public');
        } else {
            $file = null;
        }
        Article::create([
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'image' => $file,
        ]);
        return redirect()->route('articles.index')->with('success', 'Thêm thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::findOrFail($id);
        return view('articles.show', compact('article'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $articles = Article::findOrFail($id);
        return view('articles.edit', compact('articles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Xác thực dữ liệu
        $request->validate([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ], [
            'name.required' => 'Vui lòng nhập tên.',
            'name.string' => 'Tên phải là chuỗi ký tự.',
            'name.max' => 'Tên không được vượt quá 255 ký tự.',

            'title.required' => 'Vui lòng nhập tiêu đề.',
            'title.string' => 'Tiêu đề phải là chuỗi ký tự.',
            'title.max' => 'Tiêu đề không được vượt quá 255 ký tự.',

            'content.required' => 'Vui lòng nhập nội dung.',
            'content.string' => 'Nội dung phải là chuỗi ký tự.',

            'image.image' => 'File phải là một hình ảnh.',
            'image.mimes' => 'Ảnh chỉ cho phép các định dạng jpeg, png, jpg, gif, svg.',
            'image.max' => 'Ảnh không được vượt quá 2MB.'
        ]);

        // Tìm đối tượng Article cần cập nhật
        $article = Article::findOrFail($id);

        // Xử lý file ảnh nếu có
        if ($request->hasFile('image')) {
            $file = $request->file('image')->store('uploads/article', 'public');
        } else {
            $file = $article->image; // Giữ nguyên ảnh cũ nếu không có ảnh mới
        }

        // Cập nhật bản ghi
        $article->update([
            'name' => $request->input('name'),
            'title' => $request->input('title'),
            'content' => $request->input('content'),
            'image' => $file,
        ]);

        return redirect()->route('articles.index')->with('success', 'Cập nhật thành công');
    }

    public function destroy(string $id)
    {
        $article = Article::findOrFail($id);
        if ($article->image) {
            $imagePath = storage_path('app/public/' . $article->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Bài viết đã được xóa thành công.');
    }
}
