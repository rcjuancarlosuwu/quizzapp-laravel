<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'problem_id',
        'alternative_id',
        'question',
        'value',
    ];

    public function problem()
    {
        return $this->belongsTo(Problem::class);
    }

    public function answer()
    {
        return $this->hasOne(Alternative::class);
    }

    public function alternatives()
    {
        return $this->hasMany(Alternative::class);
    }
}
