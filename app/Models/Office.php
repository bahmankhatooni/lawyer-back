<?php
// فایل: app/Models/Office.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'name',
        'address',
        'phone',
        'license_number',
        'max_lawyers'
    ];

    /**
     * رابطه یک به چند با وکلا
     * هر دفتر می‌تواند چندین وکیل داشته باشد
     */
    public function lawyers()
    {
        return $this->hasMany(Lawyer::class);
    }

    /**
     * رابطه یک به چند با پرونده‌ها
     * هر دفتر می‌تواند چندین پرونده داشته باشد
     */
    public function files()
    {
        return $this->hasMany(File::class);
    }

    /**
     * رابطه یک به چند با موکلین
     * هر دفتر می‌تواند چندین موکل داشته باشد
     */
    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
