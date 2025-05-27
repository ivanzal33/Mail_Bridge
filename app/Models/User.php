<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Helpers\AesGcmHelper;
use Illuminate\Support\Facades\Mail;




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






    public function setEmailAttribute($value)
    {
        $key = base64_decode(explode(':', env('AES_GCM_KEY'))[1]);
        $this->attributes['email'] = AesGcmHelper::encrypt($value, $key);
    }

    public function getEmailAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        $key = base64_decode(explode(':', env('AES_GCM_KEY'))[1]);
        try {
            return AesGcmHelper::decrypt($value, $key) ?: $value;
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setRoleAttribute($value)
    {
        $key = base64_decode(explode(':', env('AES_GCM_KEY'))[1]);
        $this->attributes['role'] = AesGcmHelper::encrypt($value, $key);
    }

    public function getRoleAttribute($value)
    {
        if (empty($value)) {
            return $value;
        }
        $key = base64_decode(explode(':', env('AES_GCM_KEY'))[1]);
        try {
            return AesGcmHelper::decrypt($value, $key) ?: $value;
        } catch (\Exception $e) {
            return $value;
        }
    }

// Генерация и отправка кода на email
    public function generateTwoFactorCode()
    {
        $this->two_factor_code = random_int(100000, 999999);
        $this->two_factor_expires_at = now()->addMinutes(10);
        $this->save();

        Mail::to($this->email)->send(new \App\Mail\TwoFactorCodeMail($this));
    }

    // Сброс кода после подтверждения или истечения
    public function resetTwoFactorCode()
    {
        $this->two_factor_code = null;
        $this->two_factor_expires_at = null;
        $this->save();
    }



}
