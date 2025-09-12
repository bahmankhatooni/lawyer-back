<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['fname','lname', 'national_code', 'phone', 'address','email'];

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
