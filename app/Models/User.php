<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table = 'users';
    
    protected $primaryKey = 'id';
    protected $fillable = [
        'email',
        'password',
    ];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function permission() {
        return $this->hasOne(Role::class, 'user_id', 'id');
    }
}
