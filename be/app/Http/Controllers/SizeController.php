<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Lấy tham số tìm kiếm từ form
        $searchTerm = $request->input('search');
        $status = $request->input('status');
        
        $query = Size::query(); // Dùng Size model thay vì Product
        
        // Tìm kiếm theo tên size nếu có
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%'); // Tìm kiếm theo tên
        }
        
        // Tìm kiếm theo trạng thái nếu có
        if ($status) {
            $query->where('status', $status); // Tìm kiếm theo trạng thái
        }
        
        // Lấy kết quả tìm kiếm và phân trang
        $sizes = $query->orderBy('id', 'desc')->paginate(5);
        
        return view('blocks.size.index', compact('sizes'));
    }
    
    public function create()
    {
        return view('blocks.size.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|unique:sizes,name',
    ], [
        'name.unique' => 'Kích thước này đã tồn tại, vui lòng chọn tên khác!',
    ]);

    Size::create($validated);

    return redirect()->route('sizes.index')->with('Thành công', 'Kích thước đã được tạo thành công!');
}



    public function edit($id)
    {
        $size = Size::findOrFail($id);
        return view('blocks.size.edit', compact('size'));
    }

    public function update(Request $request, $id)
    {
        $size = Size::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:active,inactive',
        ]);

        $size->update($validated);
        return redirect()->route('sizes.index')->with('Thành công', 'Kích thước đã được cập nhật thành côgn!');
    }

    public function destroy($id)
    {
        $size = Size::findOrFail($id);
        $size->delete();
        return redirect()->route('sizes.index')->with('Thành công', 'kích thước đã được xóa thành công!');
    }
}
