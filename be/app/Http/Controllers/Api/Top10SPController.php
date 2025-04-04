<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class Top10SPController extends Controller
{
    public function top10() {
        return response()->json([
            'top_selling_products' => $this->getTopSellingProducts()
        ]);
    }

    private function getTopSellingProducts() {
        $products = Product::join('product_details', 'products.id', '=', 'product_details.product_id')
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

        // Chuyển đổi hình ảnh sang base64
        foreach ($products as $product) {
            if ($product->image) {
                $imagePath = storage_path('app/public/' . $product->image); // Điều chỉnh theo đường dẫn thực tế
                if (file_exists($imagePath)) {
                    $imageData = file_get_contents($imagePath);
                    $product->image = 'data:image/' . pathinfo($imagePath, PATHINFO_EXTENSION) . ';base64,' . base64_encode($imageData);
                } else {
                    $product->image = null; // Nếu ảnh không tồn tại
                }
            }
        }

        return $products;
    }
}