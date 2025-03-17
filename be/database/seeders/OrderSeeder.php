<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Voucher;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run()
    {
        // Tạo 10 đơn hàng giả
        Order::factory(10)->create();
    }
}
