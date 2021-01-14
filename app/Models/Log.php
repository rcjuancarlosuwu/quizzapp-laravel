<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'problem_id',
        'level_id',
        'block_id',
        'state_key',
        'correct_questions_id',
        'ppm',
        'duration',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function level()
    {
        return $this->hasOne(Alternative::class);
    }

    public function block()
    {
        return $this->hasOne(Block::class);
    }

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    public function scores()
    {
        return $this->hasMany(Score::class);
    }
}
