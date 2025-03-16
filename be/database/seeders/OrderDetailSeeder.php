<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ProductDetail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */  public function run()
    {
        // Lặp qua các đơn hàng và tạo order_details
        $orders = Order::all();

        foreach ($orders as $order) {
            // Tạo 1-3 chi tiết đơn hàng cho mỗi đơn hàng
            for ($i = 0; $i < rand(1, 3); $i++) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_detail_id' => ProductDetail::inRandomOrder()->first()->id, // Lấy product_detail ngẫu nhiên
                    'price' => fake()->randomFloat(2, 50, 200),
                    'quantity' => rand(1, 5),
                ]);
            }
        }
    }
}
