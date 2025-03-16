<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Voucher;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        return [
            'username' => fake()->name(),
            'voucher_id' => Voucher::inRandomOrder()->first()?->id, // Ngẫu nhiên chọn một voucher
            'status' => fake()->randomElement(['pending', 'completed', 'cancelled']), // Trạng thái đơn hàng
            'deliver_fee' => fake()->randomFloat(2, 10, 50), // Phí giao hàng ngẫu nhiên
            'customer_id' => Customer::inRandomOrder()->first()?->id, // Chọn ngẫu nhiên khách hàng
            'total_amount' => fake()->randomFloat(2, 100, 1000), // Tổng số tiền từ 100 đến 1000
            'address' => fake()->address(),
            'phone_number' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'total_price' => fake()->randomFloat(2, 150, 1500), // Tổng giá trị đơn hàng
            'note' => fake()->sentence(), // Ghi chú ngẫu nhiên
        ];
    }
}
