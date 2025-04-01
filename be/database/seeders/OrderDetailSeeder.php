<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class OrderDetailSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $orders = DB::table('orders')->pluck('id'); // Lấy danh sách các đơn hàng từ bảng orders
        $productDetails = DB::table('product_details')->pluck('id'); // Lấy danh sách product_detail_id

        foreach ($orders as $orderId) {
            $numProducts = rand(1, 5); // Mỗi đơn hàng có từ 1 đến 5 sản phẩm

            for ($i = 0; $i < $numProducts; $i++) {
                // Chọn ngẫu nhiên product_detail_id từ bảng product_details
                $productDetailId = $faker->randomElement($productDetails);
                $quantity = rand(1, 3); // Số lượng sản phẩm ngẫu nhiên
                $price = rand(100000, 500000); // Giá sản phẩm ngẫu nhiên
                $total = $quantity * $price; // Tổng tiền cho sản phẩm

                // Tạo bản ghi mới cho order_details
                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'product_detail_id' => $productDetailId,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total_price' => $total,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
