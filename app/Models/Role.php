<?php
// فایل: app/Models/Role.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'name',
        'permissions'
    ];

    /**
     * فیلدهای که باید به JSON تبدیل شوند
     */
    protected $casts = [
        'permissions' => 'array'
    ];

    /**
     * رابطه یک به چند با کاربران
     * هر نقش می‌تواند متعلق به چندین کاربر باشد
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
