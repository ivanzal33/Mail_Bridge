<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function messengers()
    {
        return $this->belongsToMany(Messenger::class)->withPivot('relation_type');
    }

    public function messengersForUser($userId)
    {
        return $this->messengers()->wherePivot('user_id', $userId);
    }


    protected $fillable = ['email', 'token'];

}
