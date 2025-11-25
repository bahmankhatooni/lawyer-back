<?php
// فایل: app/Models/Client.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'national_code',
        'phone',
        'address',
        'email',
        'office_id'
    ];

    /**
     * رابطه چند به یک با دفتر
     * هر موکل می‌تواند متعلق به یک دفتر باشد (یا مستقل باشد)
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * رابطه چند به چند با پرونده‌ها
     * هر موکل می‌تواند چندین پرونده داشته باشد و هر پرونده چندین موکل
     */
    public function files()
    {
        return $this->belongsToMany(File::class, 'client_file');
    }

    /**
     * دسترسی برای گرفتن نام کامل موکل
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
