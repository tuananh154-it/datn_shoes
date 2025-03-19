<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Article;

class HomeController extends Controller {
    public function index() {
        return response()->json([
            'banners' => Banner::whereNull('deleted_at')->select('id', 'image_url', 'link')->get(),
            'products' => Product::whereNull('deleted_at')->where('status', 'active')->select('id', 'name', 'image', 'price', 'description', 'category_id', 'brand_id')->get(),
            'articles' => Article::select('id', 'name', 'title', 'image')->get()
        ]);
    }
}
