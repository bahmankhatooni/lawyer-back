<?php
// فایل: database/migrations/[...]_create_files_table.php

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
        Schema::create('files', function (Blueprint $table) {
            $table->id(); // کلید اصلی
            $table->string('file_number')->unique(); // شماره پرونده
            $table->string('title'); // عنوان پرونده
            $table->text('description')->nullable(); // شرح پرونده
            $table->enum('status', ['open', 'closed', 'pending'])->default('open'); // وضعیت پرونده
            $table->date('start_date'); // تاریخ شروع پرونده
            $table->date('end_date')->nullable(); // تاریخ پایان پرونده
            $table->unsignedBigInteger('lawyer_id'); // کلید خارجی به وکیل
            $table->unsignedBigInteger('office_id')->nullable(); // کلید خارجی به دفتر حقوقی
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
