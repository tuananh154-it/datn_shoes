<?php

namespace App\Http\Controllers;

use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sizes = Size::all();
        return view('blocks.size.index', compact('sizes'));
    }

    public function create()
    {
        return view('blocks.size.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
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
