<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách category_id và brand_id có sẵn
        $categories = Category::pluck('id')->toArray();
        $brands = Brand::pluck('id')->toArray();

        // Kiểm tra nếu không có category hoặc brand thì không thêm product
        if (empty($categories) || empty($brands)) {
            return;
        }

        // Tạo 10 sản phẩm mẫu
        for ($i = 0; $i < 10; $i++) {
            Product::create([
                'name' => fake()->word(),
                'description' => fake()->sentence(),
                'category_id' => $categories[array_rand($categories)],
                'brand_id' => $brands[array_rand($brands)],
                'price' => fake()->randomFloat(2, 10, 1000), // Giá từ 10 đến 1000
                'image' => fake()->imageUrl(200, 200, 'products'),
                'status' => fake()->boolean(),
            ]);
        }
    }
}
