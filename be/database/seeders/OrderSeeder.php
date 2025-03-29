<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $productDetails = DB::table('product_details')->pluck('id')->toArray();
        $users = DB::table('users')->pluck('id')->toArray(); // Lấy danh sách user_id

        for ($i = 0; $i < 1000; $i++) {
            $orderDate = Carbon::now()->subDays(rand(1, 365)); // Trong vòng 1 năm gần đây
            $userId = $faker->randomElement($users);
            $totalPrice = 0;
            $deliverFee = rand(20000, 50000); // Phí vận chuyển ngẫu nhiên

            $orderId = DB::table('orders')->insertGetId([
                'user_id' => $userId,
                'username' => $faker->userName,
                'voucher_id' => null,
                'status' => $faker->randomElement([
                    'waiting_for_confirmation',
                    'waiting_for_pickup',
                    'waiting_for_delivery',
                    'delivered',
                    'returned',
                    'cancelled'
                ]),
                'deliver_fee' => $deliverFee,
                'payment_status' => $faker->randomElement(['paid', 'unpaid']),
                'payment_method' => $faker->randomElement(['credit_card', 'cash_on_delivery', 'paypal']),
                'address' => $faker->address,
                'phone_number' => $faker->phoneNumber,
                'email' => $faker->email,
                'total_price' => 0,
                'note' => $faker->sentence,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            $numProducts = rand(1, 5);

            for ($j = 0; $j < $numProducts; $j++) {
                // Kiểm tra xem có sản phẩm không
                if (!empty($productDetails)) {
                    $productDetailId = $faker->randomElement($productDetails);
                } else {
                    continue; // Bỏ qua nếu không có sản phẩm nào
                }

                $quantity = rand(1, 3);
                $price = rand(100000, 500000);
                $total = $quantity * $price;

                DB::table('order_details')->insert([
                    'order_id' => $orderId,
                    'product_detail_id' => $productDetailId,
                    'price' => $price,
                    'quantity' => $quantity,
                    'total_price' => $total,
                    'created_at' => $orderDate,
                    'updated_at' => $orderDate,
                ]);

                $totalPrice += $total;
            }

            DB::table('orders')->where('id', $orderId)->update(['total_price' => $totalPrice + $deliverFee]);
        }
    }
}
