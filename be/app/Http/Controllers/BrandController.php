<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        // Lấy các tham số tìm kiếm từ form
        $searchTerm = $request->input('search');
        $status = $request->input('status');
    
        $query = Brand::query();
    
        // Tìm kiếm theo tên nếu có
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
    
        // Lọc theo trạng thái nếu có
        if ($status !== null && in_array($status, ['active', 'inactive'])) {
            $query->where('status', $status);
        }
    
        // Lấy danh sách thuong hieu sau khi lọc và phân trang
        $brands = $query->orderBy('id', 'desc')->paginate(5);
    
        return view('brands.index', compact('brands'));
    }
    

    public function create()
    {
        return view('brands.create');
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
                        $fail('Tên thương hiệu không được để trống.');
                        return;
                    }
    
                    $normalized_name = trim(strtolower($value));
    
                    if (Brand::whereRaw('LOWER(name) = ?', [$normalized_name])->exists()) {
                        $fail('Tên thương hiệu đã tồn tại.');
                    }
                },
            ],
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Tên thương hiệu không được để trống.',
            'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);
    
        Brand::create($request->all());
    
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được thêm thành công!');
    }
    
    

    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(Request $request, Brand $brand)
    {
        $request->validate([
            'name' => [
                'required', // Bắt buộc nhập
                'string',
                'max:255',
                function ($attribute, $value, $fail) use ($brand) {
                    if (empty($value)) {
                        $fail('Tên thương hiệu không được để trống.');
                        return;
                    }
    
                    $normalized_name = trim(strtolower($value));
    
                    if (Brand::whereRaw('LOWER(name) = ?', [$normalized_name])
                        ->where('id', '!=', $brand->id) // Loại trừ thương hiệu đang chỉnh sửa
                        ->exists()) {
                        $fail('Tên thương hiệu đã tồn tại.');
                    }
                },
            ],
            'status' => 'required|in:active,inactive',
        ], [
            'name.required' => 'Tên thương hiệu không được để trống.',
            'name.max' => 'Tên thương hiệu không được vượt quá 255 ký tự.',
            'status.required' => 'Trạng thái không được để trống.',
            'status.in' => 'Trạng thái không hợp lệ.',
        ]);
    
        $brand->update($request->all());
    
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được cập nhật!');
    }
    


    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('brands.index')->with('success', 'Thương hiệu đã được xóa!');
    }
}
