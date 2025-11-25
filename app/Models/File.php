<?php
// فایل: app/Models/File.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'case_number',
        'title',
        'description',
        'status',
        'start_date',
        'end_date',
        'lawyer_id',
        'office_id'
    ];

    /**
     * فیلدهای که باید به نوع خاصی تبدیل شوند
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    /**
     * رابطه چند به یک با وکیل
     * هر پرونده متعلق به یک وکیل است
     */
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }

    /**
     * رابطه چند به یک با دفتر
     * هر پرونده می‌تواند متعلق به یک دفتر باشد (یا مستقل باشد)
     */
    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    /**
     * رابطه چند به چند با موکلین
     * هر پرونده می‌تواند چندین موکل داشته باشد و هر موکل چندین پرونده
     */
    public function clients()
    {
        return $this->belongsToMany(Client::class, 'client_file');
    }

    /**
     * رابطه یک به چند با جلسات
     * هر پرونده می‌تواند چندین جلسه داشته باشد
     */
    public function meetings()
    {
        return $this->hasMany(Meeting::class);
    }
}
