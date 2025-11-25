<?php
// فایل: app/Models/Meeting.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    /**
     * فیلدهای قابل پر شدن
     */
    protected $fillable = [
        'title',
        'description',
        'meeting_date',
        'location',
        'status',
        'file_id',
        'lawyer_id'
    ];

    /**
     * فیلدهای که باید به نوع خاصی تبدیل شوند
     */
    protected $casts = [
        'meeting_date' => 'datetime'
    ];

    /**
     * رابطه چند به یک با پرونده
     * هر جلسه متعلق به یک پرونده است
     */
    public function file()
    {
        return $this->belongsTo(File::class);
    }

    /**
     * رابطه چند به یک با وکیل
     * هر جلسه متعلق به یک وکیل است
     */
    public function lawyer()
    {
        return $this->belongsTo(Lawyer::class);
    }
}
