<?php

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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique(); // استفاده از نام کاربری برای لاگین
            $table->string('email')->unique()->nullable(); // ایمیل می‌تواند تهی باشد
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('first_name'); // نام
            $table->string('last_name'); // نام خانوادگی
            $table->string('national_code')->unique()->nullable(); // کد ملی

            // فیلدهای مربوط به رابطه چندریختی (Polymorphic)
            $table->unsignedBigInteger('role_id'); // کلید خارجی به نقش
            $table->morphs('userable'); // این خط دو فیلد ایجاد می‌کند: userable_id و userable_type

            $table->rememberToken();
            $table->timestamps();

        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
