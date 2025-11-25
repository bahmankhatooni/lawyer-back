<?php
// فایل: database/migrations/[...]_add_foreign_keys_to_all_tables.php

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
        Schema::table('users', function (Blueprint $table) {
            // اضافه کردن کلید خارجی role_id به جدول users
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        Schema::table('lawyers', function (Blueprint $table) {
            // اضافه کردن کلید خارجی office_id به جدول lawyers
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
        });

        Schema::table('clients', function (Blueprint $table) {
            // اضافه کردن کلید خارجی office_id به جدول clients
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
        });

        Schema::table('files', function (Blueprint $table) {
            // اضافه کردن کلیدهای خارجی به جدول files
            $table->foreign('lawyer_id')->references('id')->on('lawyers')->onDelete('cascade');
            $table->foreign('office_id')->references('id')->on('offices')->onDelete('cascade');
        });

        Schema::table('client_file', function (Blueprint $table) {
            // اضافه کردن کلیدهای خارجی به جدول واسط client_file
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
        });

        Schema::table('meetings', function (Blueprint $table) {
            // اضافه کردن کلیدهای خارجی به جدول meetings
            $table->foreign('file_id')->references('id')->on('files')->onDelete('cascade');
            $table->foreign('lawyer_id')->references('id')->on('lawyers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
        });

        Schema::table('lawyers', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['office_id']);
        });

        Schema::table('files', function (Blueprint $table) {
            $table->dropForeign(['lawyer_id']);
            $table->dropForeign(['office_id']);
        });

        Schema::table('client_file', function (Blueprint $table) {
            $table->dropForeign(['client_id']);
            $table->dropForeign(['file_id']);
        });

        Schema::table('meetings', function (Blueprint $table) {
            $table->dropForeign(['file_id']);
            $table->dropForeign(['lawyer_id']);
        });
    }
};
