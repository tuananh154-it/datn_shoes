<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Lấy các tham số tìm kiếm từ form
        $searchTerm = $request->input('search');
        $status = $request->input('status');

        $query = Category::query();

        // Tìm kiếm theo tên nếu có
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }

        // Lọc theo trạng thái nếu có
        if ($status !== null && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }

        // Lấy danh sách thuong hieu sau khi lọc và phân trang
        $categories = $query->orderBy('id', 'desc')->paginate(5);

        return view('categories.index', compact('categories'));
    }
    public function create()
    {
        return view('categories.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required', // Bắt buộc nhập
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    if (empty($value)) {
                        $fail('Tên danh mục không được để trống.');
                        return;
                    }

                    $normalized_name = trim(strtolower($value));

                    if (Category::whereRaw('LOWER(name) = ?', [$normalized_name])->exists()) {
                        $fail('Tên danh mục đã tồn tại.');
                    }
                },
            ],
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Danh mục đã được thêm thành công!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => [
                'required', // Bắt buộc nhập
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($category) {
                    if (empty($value)) {
                        $fail('Tên danh mục  không được để trống.');
                        return;
                    }

                    $normalized_name = trim(strtolower($value));

                    if (Category::whereRaw('LOWER(name) = ?', [$normalized_name])
                        ->where('id', '!=', $category->id) // Loại trừ danh mục  đang chỉnh sửa
                        ->exists()
                    ) {
                        $fail('Tên danh mục đã tồn tại.');
                    }
                },
            ],
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Tên danh mục không được để trống.',
            'name.max' => 'Tên danh mục không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);

        $category->update($request->all());

        return redirect()->route('categories.index')->with('success', 'Thương hiệu đã được cập nhật!');
    }
    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Danh mục đã được xóa thành công!');
    }
}
