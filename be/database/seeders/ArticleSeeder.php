<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $articles = [];

        for ($i = 1; $i <= 10; $i++) {
            $articles[] = [
                'name' => 'Bài viết ' . $i,
                'title' => 'Tiêu đề bài viết ' . $i,
                'content' => 'Nội dung bài viết số ' . $i . '...',
                'image' => 'article' . $i . '.jpg',
                'created_at' => now(),
                'updated_at' => now(),
                
            ];
        }

        Article::insert($articles);
    }
}
