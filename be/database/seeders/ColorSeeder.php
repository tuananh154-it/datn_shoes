<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Color;  // Đảm bảo đã import model Color

class ColorSeeder extends Seeder
{
    public function run()
    {
        // Seed 5 màu sắc mẫu
        Color::create([
            'name' => 'Red',
            'status' => 'active',
        ]);

        Color::create([
            'name' => 'Blue',
            'status' => 'active',
        ]);

        Color::create([
            'name' => 'Green',
            'status' => 'inactive',
        ]);

        Color::create([
            'name' => 'Yellow',
            'status' => 'active',
        ]);

        Color::create([
            'name' => 'Black',
            'status' => 'inactive',
        ]);
    }
}
