<?php

namespace App\Http\Controllers;

use App\Models\Log;
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

    public function problemsByStateKey($state_key)
    {
        return Log::with('problem.questions')->where('state_key', $state_key)->get()->map(function ($l) {
            return [
                'problem' => $l->problem->body,
                'questions' => $l->problem->questions->map(function ($q) use ($l) {
                    return [
                        'question' => $q->question,
                        'correct' => in_array($q->id, $l->correct_questions_id == null ? [] : explode(',', $l->correct_questions_id))
                    ];
                })
            ];
        });
    }
}
