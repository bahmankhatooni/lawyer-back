<?php
// فایل: database/migrations/[...]_create_offices_table.php

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
        Schema::create('offices', function (Blueprint $table) {
            $table->id(); // کلید اصلی
            $table->string('name'); // نام دفتر حقوقی
            $table->string('address')->nullable(); // آدرس دفتر
            $table->string('phone')->nullable(); // تلفن دفتر
            $table->string('license_number')->unique(); // شماره پروانه وکالت دفتر
            $table->integer('max_lawyers'); // حداکثر تعداد وکیل مجاز برای این دفتر
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
