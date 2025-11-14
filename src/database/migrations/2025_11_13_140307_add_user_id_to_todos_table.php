<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // すでに user_id カラムありのため、何もしない
        return;
    }

    public function down(): void
    {
        // down も空で OK（何もしない）
        return;
    }
};
