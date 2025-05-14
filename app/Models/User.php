<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public function emails()
    {
        return $this->belongsToMany(Email::class);
    }

    public function messengers()
    {
        return $this->belongsToMany(Messenger::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }



    /**
     * Поля которые видно
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'role',
        'password',
    ];

    /**
     * Поля не хотим отображать
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password'
    ];
}
