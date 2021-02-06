<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempts extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'attempt',
        'xp',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function logs()
    {
        return $this->hasMany(Log::class);
    }
}
