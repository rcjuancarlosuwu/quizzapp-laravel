<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_id',
        'alternative_id',
    ];

    public function log()
    {
        return $this->belongsTo(Log::class);
    }

    public function alternative()
    {
        return $this->belongsTo(Alternative::class);
    }
}
