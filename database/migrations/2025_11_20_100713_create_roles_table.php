<?php
// فایل: database/migrations/[...]_create_roles_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id(); // ایجاد فیلد کلید اصلی
            $table->string('name'); // نام نقش (مانند: admin, office, vakil)
            $table->json('permissions')->nullable(); // لیست دسترسی‌ها به صورت JSON
            $table->timestamps(); // فیلدهای created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
