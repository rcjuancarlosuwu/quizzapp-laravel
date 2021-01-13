<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

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
}
