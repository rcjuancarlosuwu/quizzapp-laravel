<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'code_id',
        'school_id',
        'enrollment_code',
        'nickname',
        'email',
        'semester',
    ];

    public function code()
    {
        return $this->belongsTo(Code::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }

    public function latest_log()
    {
        return $this->hasOne(Log::class)->latest();
    }

    public function attempt()
    {
        return $this->hasMany(Attempts::class);
    }
}
