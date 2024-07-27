<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Chat;
use Illuminate\Database\Eloquent\Relations\HasMany;
class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';
    protected $guarded = ['id'];

    protected $hidden = [
        'password'
    ];



    const USER_TOKEN = 'userToken';
    
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class,'created_by');
    }

    public function createNewToken($name, array $abilities = ['*'])
    {
        return $this->createToken($name, $abilities)->plainTextToken;
    }
}
