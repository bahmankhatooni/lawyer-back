<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'lawyer_id',
        'client_id'
    ];

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class,'client_id');
    }
}
