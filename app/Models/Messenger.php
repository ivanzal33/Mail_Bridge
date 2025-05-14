<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messenger extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }


    public function emails()
    {
        return $this->belongsToMany(Email::class)->withPivot('relation_type');
    }

    public function emailsForUser($userId)
    {
        return $this->emails()->wherePivot('user_id', $userId);
    }

    protected $fillable = [
        'type',  // тип мессенджера
        'value', // значение (номер телефона или ник)
    ];
}
