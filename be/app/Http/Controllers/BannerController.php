<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
{
    $banners = Banner::orderBy('id', 'desc')->paginate(10);
    $noResults = $banners->isEmpty(); // Kiểm tra danh sách rỗng

    return view('banners.list', compact('banners', 'noResults'));
}


    public function create()
    {
        return view('banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_url' => 'required|image',
            'link' => 'required'
        ]);

        // Xử lý upload file
        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url')->store('uploads/banners', 'public');
        } else {
            $file = null;
        }
        // Lưu vào database
        Banner::create([
            'image_url' => $file,
            'link' => $request->link,
        ]);

        return redirect()->route('banners.index')->with('success', 'Thê m banner thành công');
    }
    public function show($id){
        $banners = Banner::findOrFail($id);
        return view('banners.show', compact('banners'));
    }


    public function edit($id)
    {
        $banner = Banner::findOrFail($id);
        return view('banners.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::findOrFail($id);

        $request->validate([
            'link' => 'required|string|max:255',
            'image_url' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image_url')) {
            if ($banner->image_url) {
                Storage::disk('public')->delete($banner->image_url);
            }
            $banner->image_url = $request->file('image_url')->store('uploads/banners', 'public');
        }

        $banner->update([
            'link' => $request->link,
        ]);

        return redirect()->route('banners.index')->with('success', 'Banner đã được cập nhật thành công!');
    }

    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();
        return redirect()->route('banners.index')->with('success', 'Banner đã được xóa thành công!');
    }

    public function restore($id)
    {
        $banner = Banner::withTrashed()->findOrFail($id);
        $banner->restore();
        return redirect()->route('banners.index')->with('success', 'Banner đã được khôi phục thành công!');
    }
}
