<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class TodoFactory extends Factory
{
    public function definition(): array
    {
        // カテゴリがなければ自動で3件追加
        if (!Category::exists()) {
            Category::insert([
                ['name' => '仕事', 'created_at' => now(), 'updated_at' => now()],
                ['name' => 'プライベート', 'created_at' => now(), 'updated_at' => now()],
                ['name' => '勉強', 'created_at' => now(), 'updated_at' => now()],
            ]);
        }

        // ランダムにカテゴリを取得
        $categoryId = Category::inRandomOrder()->value('id') ?? 1;

        return [
            'content' => mb_substr($this->faker->realText(30), 0, 20),
            'category_id' => $categoryId,
            'deadline' => $this->faker->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
