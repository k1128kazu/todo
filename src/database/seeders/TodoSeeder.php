<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Todo;

class TodoSeeder extends Seeder
{
    public function run(): void
    {
        // Todoを25件自動生成
        Todo::factory()->count(25)->create();
    }
}
