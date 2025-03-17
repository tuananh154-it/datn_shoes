<?php

namespace Database\Factories;

use App\Models\OrderDetail;
use App\Models\Order;
use App\Models\ProductDetail;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderDetailFactory extends Factory
{
    protected $model = OrderDetail::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::inRandomOrder()->first()->id, // Chọn đơn hàng ngẫu nhiên
            'product_detail_id' => ProductDetail::inRandomOrder()->first()->id, // Chọn sản phẩm chi tiết ngẫu nhiên
            'price' => fake()->randomFloat(2, 50, 500), // Giá sản phẩm ngẫu nhiên từ 50 đến 500
            'quantity' => fake()->numberBetween(1, 5), // Số lượng ngẫu nhiên từ 1 đến 5
        ];
    }
}
