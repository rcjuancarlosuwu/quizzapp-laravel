<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{

    public function register(Request $request)
    {
        Student::create($request->all());
        return 'token';
    }

    public function studentLevel(Request $request)
    {
        return Log::where('student_id', $request->id)->max('level_id');
    }

    public function saveLog(Request $request)
    {
        return Log::create([
            'student_id' => $request->student_id,
            'level_id' => $request->level_id,
            'block_id' => $request->block_id,
            'problem_id' => $request->problem_id,
            'correct_questions_id' => implode(',', $request->correct_questions_id),
            'ppm'  => $request->ppm,
            'duration'  => $request->duration,
        ]);
    }

    public function progress(Request $request)
    {
        return Student::with('latest_log:student_id,level_id,block_id')->find($request->student_id)->latest_log;
    }

    public function results(Request $request)
    {
        $logs = Log::where('level_id', $request->level_id)
            ->where('student_id', $request->student_id)->orderBy('id', 'desc')->take(2)->get();

        $results = [
            "block_1" => 0,
            "block_2" => 0,
            "average" => 0,
        ];

        foreach ($logs as $log) {
            if ($log->block_id == 1) {
                $results["block_1"] = count(explode(',', $log->correct_questions_id)) * 4;
            } else {
                $results["block_2"] = count(explode(',', $log->correct_questions_id)) * 4;
            }
        }

        $results["average"] = ($results["block_1"] + $results["block_2"]) / 2;

        return $results;
    }
}
