<?php
// فایل: database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // اجرای سیدر نقش‌ها
        $this->call(RoleSeeder::class);

        // اجرای سیدر کاربر ادمین
        $this->call(AdminUserSeeder::class);
    }
}
