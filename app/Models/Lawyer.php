<?php
// فایل: app/Models/Lawyer.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lawyer extends Model
{
    use HasFactory;

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'bar_association_id',
        'specialty',
        'office_id'
    ];

    /**
     * رابطه چند به یک با دفتر
     * هر وکیل می‌تواند متعلق به یک دفتر باشد (یا مستقل باشد)
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * رابطه یک به چند با پرونده‌ها
     * هر وکیل می‌تواند چندین پرونده داشته باشد
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * رابطه یک به چند با جلسات
     * هر وکیل می‌تواند چندین جلسه داشته باشد
     */
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }

    /**
     * رابطه یک به یک با کاربر
     * از طریق رابطه Polymorphic
     */
    public function user()
    {
        return $this->morphOne(User::class, 'userable');
    }

    /**
     * بررسی می‌کند که وکیل مستقل است یا خیر
     */
    public function isIndependent()
    {
        return is_null($this->office_id);
    }
}
