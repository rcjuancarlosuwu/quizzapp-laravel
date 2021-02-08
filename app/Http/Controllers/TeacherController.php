<?php

namespace App\Http\Controllers;

use App\Exports\gChartExcel;
use App\Exports\StudentExport;
use App\Models\Attempts;
use App\Models\Code;
use App\Models\Log;
use App\Models\Student;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;

class TeacherController extends Controller
{

    /**
     * Sign up Teacher
     */
    public function signUp(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|unique:teachers',
            'password' => 'required'
        ]);
        $teacher = Teacher::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
        $tokenResult = $teacher->createToken('Personal Access Token', ['teacher']);
        $token = $tokenResult->token;

        return response()->json([
            'message' => 'Successfully created teacher!',
            "token" => $tokenResult->accessToken,
            'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
        ], 201);
    }

    /**
     * Sign In and Create Token
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if (Teacher::where('email', $request->email)->count() <= 0) return response(array("message" => "Email does not exist"), 400);
        $teacher = Teacher::where('email', $request->email)->first();

        if (password_verify($request->password, $teacher->password)) {

            $tokenResult = $teacher->createToken('Personal Access Token', ['teacher']);
            $token = $tokenResult->token;

            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            return response(
                array(
                    "message" => "Sign In Successful",
                    "data" => [
                        "teacher" => $teacher,
                        "token" => $tokenResult->accessToken,
                        'expires_at' => Carbon::parse($token->expires_at)->toDateTimeString()
                    ]
                ),
                200
            );
        } else {
            return response(array("message" => "Wrong Credentials."), 400);
        }
    }

    /**
     * Return Teacher
     */
    public function teacher(Request $request)
    {
        return response()->json($request->user());
    }

    public function allStudentsExcel()
    {
        return Excel::download(new StudentExport, 'estudiantes.xlsx');
    }

    // old controllers
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
        $attemtps = Attempts::where('student_id', $id)->get();
        foreach ($attemtps as $a) {
            for ($i = 1; $i <= 3; $i++) {
                $logs = Log::with('problem.questions')
                    ->where('attempt_id', $a->id)
                    ->where('student_id', $id)
                    ->where('level_id', $i)->get();

                $a['level_' . $i] = $logs->map(function ($log) {
                    return [
                        "id" => $log->id,
                        "state_key" => $log->state_key,
                        "level_id" => $log->level_id,
                        "block_id" => $log->block_id,
                        "score" => $log->correct_questions_id == null ? 0 : count(explode(',', $log->correct_questions_id)) * 5,
                        "correct_questions_id" => explode(',', $log->correct_questions_id),
                        "ppm" => $log->ppm,
                        "duration" => $log->duration,
                        "started_at" => date("Y-m-d H:i:s", strtotime($log->created_at) - $log->duration),
                        "completed_at" => date("Y-m- H:i:s", strtotime($log->created_at)),
                        "problem" => $log->problem,
                        "appreciation" => $log->appreciation,
                    ];
                });
            }
        }
        $response['attempts'] = $attemtps;
        return $response;
    }

    public function gChart($code_id)
    {
        $logs = Log::whereHas('student', function ($q) use ($code_id) {
            $q->where('code_id', $code_id);
        })->get();
        return [
            'scores' => [
                [
                    'name' => 'Nivel 1',
                    'value' => $logs->where('level_id', 1)->avg('score') ?? 0
                ],
                [
                    'name' => 'Nivel 2',
                    'value' => $logs->where('level_id', 2)->avg('score') ?? 0
                ],
                [
                    'name' => 'Nivel 3',
                    'value' => $logs->where('level_id', 3)->avg('score') ?? 0
                ],
            ],
            'ppms' => [
                [
                    'name' => 'Nivel 1',
                    'value' => $logs->where('level_id', 1)->avg('ppm') ?? 0
                ],
                [
                    'name' => 'Nivel 2',
                    'value' => $logs->where('level_id', 2)->avg('ppm') ?? 0
                ],
                [
                    'name' => 'Nivel 3',
                    'value' => $logs->where('level_id', 3)->avg('ppm') ?? 0
                ],
            ],
        ];
    }

    public function gChartExcel($code_id)
    {
        return Excel::download(new gChartExcel($code_id), 'Estudiantes.xlsx');
    }
}
