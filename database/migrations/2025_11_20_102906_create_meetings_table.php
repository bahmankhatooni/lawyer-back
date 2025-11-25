<?php
// فایل: database/migrations/[...]_create_meetings_table.php

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
        Schema::create('meetings', function (Blueprint $table) {
            $table->id(); // کلید اصلی
            $table->string('title'); // عنوان جلسه
            $table->text('description')->nullable(); // شرح جلسه
            $table->dateTime('meeting_date'); // تاریخ و زمان جلسه
            $table->string('location')->nullable(); // محل تشکیل جلسه
            $table->enum('status', ['scheduled', 'completed', 'cancelled'])->default('scheduled'); // وضعیت جلسه
            $table->unsignedBigInteger('file_id'); // کلید خارجی به پرونده مربوطه
            $table->unsignedBigInteger('lawyer_id'); // کلید خارجی به وکیل
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meetings');
    }
};
