<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Log;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    public function allStudents()
    {
        return Student::with('code', 'school')->get();
    }

    public function allRooms()
    {
        return Code::all()->map(function ($code) {
            return [
                'id' => $code->id,
                'code' => $code->code,
                'description' => $code->description,
                'registered_students' => $code->students()->count(),
                'total_students' => count(explode(',', $code->enrollment_codes)),
                'created_at' => $code->created_at,
            ];
        });;
    }

    public function studentInfo($id)
    {
        $response = [];
        $response['student'] = Student::with('school')->find($id);
        for ($i = 1; $i <= 3; $i++) {
            $response['level_' . $i] = Log::with('problem.questions')
                ->where('student_id', $id)
                ->where('level_id', $i)->get()->map(function ($log) {
                    return [
                        "id" => $log->id,
                        "level_id" => $log->level_id,
                        "block_id" => $log->block_id,
                        "score" => count(explode(',', $log->correct_questions_id)) * 4,
                        "ppm" => $log->ppm,
                        "duration" => $log->duration,
                        "started_at" => date("Y-m-d H:i:s", strtotime($log->created_at) - $log->duration),
                        "completed_at" => date("Y-m-d H:i:s", strtotime($log->created_at)),
                        "problem" => $log->problem,
                    ];
                });
        }
        return $response;
    }

    public function roomInfo()
    {
    }
}
