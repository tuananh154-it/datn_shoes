<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::all();

        if ($banners->isEmpty()) {
            return response()->json([
                'message' => 'Không có banner nào được tìm thấy!'
            ], 404);
        }

        return response()->json($banners->map(function ($banner) {
            return [
                'id' => $banner->id,
                'image_url' => $this->getImageAsBase64($banner->image_url),
                'link' => $banner->link,
                'created_at' => $banner->created_at,
                'updated_at' => $banner->updated_at,
            ];
        }), 200);
    }
    public function show($id)
    {
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'message' => 'Banner không tồn tại!'
            ], 404);
        }

        return response()->json([
            'id' => $banner->id,
            'image_url' => $this->getImageAsBase64($banner->image_url),
            'link' => $banner->link,
            'created_at' => $banner->created_at,
            'updated_at' => $banner->updated_at,
        ], 200);
    }
    private function getImageAsBase64($imagePath)
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            $imageData = Storage::disk('public')->get($imagePath);
            $mimeType = mime_content_type(storage_path('app/public/' . $imagePath));
            return 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
        }
        return null;
    }
}
