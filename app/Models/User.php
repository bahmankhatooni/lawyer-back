<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'fname', 'lname', 'username','phone', 'email', 'password', 'role_id','profile_image'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
//    protected $appends = ['avatar_url'];
//
//    public function getAvatarAttribute()
//    {
//        if ($this->profile_image) {
//            return asset('storage/' . $this->profile_image);
//        }
//        return null;
//    }

}
