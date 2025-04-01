<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;
use App\Models\Article;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller {
    public function index() {
        return response()->json([
            'banners' => Banner::whereNull('deleted_at')->select('id', 'image_url', 'link')->get(),
            'products' => Product::whereNull('deleted_at')->where('status', 'active')->select('id', 'name', 'image', 'price', 'description', 'category_id', 'brand_id')->get(),
            'articles' => Article::select('id', 'name', 'title', 'image')->get(),
            'top_selling_products' => $this->getTopSellingProducts()
        ]);
    }

    private function getTopSellingProducts() {
        return Product::join('product_details', 'products.id', '=', 'product_details.product_id')
            ->join('order_details', 'product_details.id', '=', 'order_details.product_detail_id')
            ->select(
                'products.id', 
                'products.name', 
                'products.image', 
                'products.price', 
                'products.description', 
                'products.category_id', 
                'products.brand_id', 
                DB::raw('SUM(order_details.quantity) as total_sold')
            )
            ->whereNull('order_details.deleted_at')
            ->groupBy(
                'products.id', 'products.name', 'products.image', 'products.price', 
                'products.description', 'products.category_id', 'products.brand_id'
            )
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();
    }
}
