<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // 必要数だけ用意（例）
        Category::insert([
            ['name' => '仕事',     'created_at' => now(), 'updated_at' => now()],
            ['name' => 'プライベート', 'created_at' => now(), 'updated_at' => now()],
            ['name' => '勉強',     'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
