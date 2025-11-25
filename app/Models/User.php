<?php
// فایل: app/Models/User.php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'first_name',
        'last_name',
        'national_code',
        'role_id',
        'userable_id',
        'userable_type',
        'is_active',
        'phone',
        'address'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean'
    ];

    /**
     * فیلد username برای احراز هویت استفاده شود
     */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * رابطه چند به یک با نقش
     * هر کاربر یک نقش دارد
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * رابطه Polymorphic با مدل‌های وکیل و دفتر
     * کاربر می‌تواند یک وکیل یا یک دفتر باشد
     */
    public function userable()
    {
        return $this->morphTo();
    }

    /**
     * بررسی می‌کند که کاربر ادمین است یا خیر
     */
    public function isAdmin()
    {
        return $this->role->name === 'admin';
    }

    /**
     * بررسی می‌کند که کاربر دفتر حقوقی است یا خیر
     */
    public function isOffice()
    {
        return $this->role->name === 'office';
    }

    /**
     * بررسی می‌کند که کاربر وکیل است یا خیر
     */
    public function isLawyer()
    {
        return $this->role->name === 'vakil';
    }

    /**
     * دسترسی برای گرفتن نام کامل کاربر
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
