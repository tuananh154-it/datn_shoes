<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Top10SPController extends Controller
{
    public function top10()
    {
        return response()->json([

            'top_selling_products' => $this->getTopSellingProducts()
        ]);
    }
    private function getTopSellingProducts()
    {
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
                'products.id',
                'products.name',
                'products.image',
                'products.price',
                'products.description',
                'products.category_id',
                'products.brand_id'
            )
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();
    }
}
