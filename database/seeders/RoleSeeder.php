<?php
// فایل: database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * اجرای سیدر
     */
    public function run(): void
    {
        // ایجاد نقش‌های اصلی سیستم
        $roles = [
            [
                'name' => 'admin',
                'permissions' => json_encode(['all']) // دسترسی کامل
            ],
            [
                'name' => 'office',
                'permissions' => json_encode(['manage_lawyers', 'manage_files', 'manage_clients', 'view_reports'])
            ],
            [
                'name' => 'vakil',
                'permissions' => json_encode(['manage_files', 'manage_clients', 'view_reports'])
            ]
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
