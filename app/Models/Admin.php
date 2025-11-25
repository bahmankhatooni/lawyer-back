<?php
// فایل: app/Models/Admin.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'employee_id',
        'department'
    ];

    /**
     * رابطه یک به یک با کاربر
     * از طریق رابطه Polymorphic
     */
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }
}
