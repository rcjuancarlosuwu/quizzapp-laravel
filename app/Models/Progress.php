<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Progress extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'level_id',
        'block_id',
    ];

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function level()
    {
        return $this->hasOne(Alternative::class);
    }

    public function block()
    {
        return $this->hasOne(Block::class);
    }
}
