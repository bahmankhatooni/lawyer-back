<?php
// فایل: database/migrations/[...]_create_clients_table.php

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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();// کلید اصلی
            $table->string('first_name'); // نام موکل
            $table->string('last_name'); // نام خانوادگی موکل
            $table->string('national_code')->unique(); // کد ملی
            $table->string('phone')->nullable(); // تلفن همراه
            $table->text('address')->nullable(); // آدرس
            $table->string('email')->nullable(); // ایمیل
            $table->unsignedBigInteger('office_id')->nullable(); // کلید خارجی به دفتر حقوقی (مالک موکل)
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
