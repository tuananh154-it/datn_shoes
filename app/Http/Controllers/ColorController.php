<?php

namespace App\Http\Controllers;

use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $searchTerm = $request->input('search');
        $status = $request->input('status');
        
        $query = Color::query(); 
        
        if ($searchTerm) {
            $query->where('name', 'like', '%' . $searchTerm . '%');
        }
        
      
        if ($status) {
            $query->where('status', $status); 
        }
        $colors = $query->orderBy('id', 'desc')->paginate(5);
        
        return view('blocks.color.index', compact('colors'));
    }
    public function create()
    {
        return view('blocks.color.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        Color::create($validated);
        return redirect()->route('colors.index')->with('Thành công', 'Màu đã được tạo thành công!');
    }


    public function edit($id)
    {
        $color = Color::findOrFail($id);
        return view('blocks.color.edit', compact('color'));
    }

    public function update(Request $request, $id)
    {
        $color = Color::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string',
            'status' => 'required|in:active,inactive', 
        ]);

        $color->update($validated);
        return redirect()->route('colors.index')->with('Thành công', 'Màu đã được cập nhật thành côgn!');
    }

    public function destroy($id)
    {
        $color = Color::findOrFail($id);
        $color->delete();
        return redirect()->route('colors.index')->with('Thành công', 'Màu đã được xóa thành công!');
    }

}
