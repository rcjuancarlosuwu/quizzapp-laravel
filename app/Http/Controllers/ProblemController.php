<?php

namespace App\Http\Controllers;

use App\Models\Problem;

class ProblemController extends Controller
{
    public function getRandomProblem($level, $block)
    {
        return Problem::with('questions', 'questions.alternatives')
            ->where('level_id', $level)
            ->where('block_id', $block)
            ->get()->random(1);
    }
}
