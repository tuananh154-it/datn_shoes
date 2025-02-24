<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Lấy danh sách user_id thực tế từ bảng users
        $userIds = DB::table('users')->pluck('id')->toArray();
        $productIds = DB::table('products')->pluck('id')->toArray();

        if (empty($userIds) || empty($productIds)) {
            return; // Nếu không có user hoặc product, không chạy seeder
        }

        for ($i = 0; $i < 10; $i++) {
            DB::table('comments')->insert([
                'user_id' => $faker->randomElement($userIds),
                'product_id' => $faker->randomElement($productIds),
                'comment' => $faker->sentence(),
                'file' => $faker->imageUrl(200, 200, 'technics'),
                'star_rating' => $faker->numberBetween(1, 5),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

}
