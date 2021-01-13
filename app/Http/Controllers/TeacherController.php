<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Student;
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
            ];
        });;
    }
}
