<?php
// فایل: database/migrations/[...]_create_client_file_table.php

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
        Schema::create('client_file', function (Blueprint $table) {
            $table->id(); // کلید اصلی
            $table->unsignedBigInteger('client_id'); // کلید خارجی به موکل
            $table->unsignedBigInteger('file_id'); // کلید خارجی به پرونده
            $table->timestamps();

            // ایجاد ایندکس یکتا برای جلوگیری از رابطه تکراری
            $table->unique(['client_id', 'file_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_file');
    }
};
