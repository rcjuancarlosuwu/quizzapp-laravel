<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'question_id',
        'alternative',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
