<?php
// فایل: database/migrations/[...]_create_lawyers_table.php

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
        Schema::create('lawyers', function (Blueprint $table) {
            $table->id();
            $table->string('bar_association_id')->unique(); // شماره پروانه وکالت
            $table->text('specialty')->nullable(); // تخصص وکیل
            $table->unsignedBigInteger('office_id')->nullable(); // کلید خارجی به دفتر (در صورت مستقل بودن، نال می‌شود)
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyers');
    }
};
