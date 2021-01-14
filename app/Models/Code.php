<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Code extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'enrollment_codes',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }
}
