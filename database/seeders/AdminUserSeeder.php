<?php
// فایل: database/seeders/AdminUserSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * اجرای سیدر کاربر ادمین
     */
    public function run(): void
    {
        // پیدا کردن نقش ادمین
        $adminRole = Role::where('name', 'admin')->first();

        // ایجاد پروفایل ادمین
        $adminProfile = Admin::create([
            'employee_id' => 'ADM001',
            'department' => 'مدیریت سیستم'
        ]);

        // ایجاد کاربر ادمین و مرتبط کردن با پروفایل ادمین
        $adminProfile->user()->create([
            'username' => 'admin',
            'email' => 'b.khatooni@gmail.com',
            'password' => Hash::make('admin123'),
            'first_name' => 'بهمن',
            'last_name' => 'خاتونی',
            'national_code' => '09128838095',
            'role_id' => $adminRole->id,
        ]);

        $this->command->info('کاربر ادمین با مشخصات زیر ایجاد شد:');
        $this->command->info('Username: admin');
        $this->command->info('Password: admin123');
        $this->command->warn('لطفاً پس از اولین ورود، رمز عبور را تغییر دهید!');
    }
}
