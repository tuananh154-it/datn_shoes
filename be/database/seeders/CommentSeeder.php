<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Comment;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CommentSeeder extends Seeder
{
    public function run()
    {
        // Lấy danh sách user và product có sẵn
        $users = User::pluck('id')->toArray();
        $products = Product::pluck('id')->toArray();

        // Kiểm tra nếu không có user hoặc product thì không thêm comment
        if (empty($users) || empty($products)) {
            return;
        }

        // Tạo 10 comment giả lập
        for ($i = 0; $i < 10; $i++) {
            Comment::create([
                'user_id' => $users[array_rand($users)],
                'product_id' => $products[array_rand($products)],
                'comment' => fake()->sentence(),
                'file' => null,
                'star_rating' => rand(1, 5),
            ]);
        }
    }
}
