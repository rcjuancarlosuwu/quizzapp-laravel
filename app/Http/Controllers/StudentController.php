<?php

namespace App\Http\Controllers;

use App\Models\Code;
use App\Models\Log;
use App\Models\School;
use App\Models\Student;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Sign up student
     */
    public function signUp(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'school_id' => 'required',
            'enrollment_code' => 'required|string',
            'email' => 'required|string|email|unique:students',
            'nickname' => 'required|string',
            'semester' => 'required'
        ]);
        $student = Student::create([
            'code_id' => Code::where('code', $request->code)->first()->id,
            'school_id' => $request->school_id,
            'enrollment_code' => $request->enrollment_code,
            'email' => $request->email,
            'nickname' => $request->nickname,
            'semester' => $request->semester
        ]);
        $tokenResult = $student->createToken('Personal Access Token', ['student']);
        $token = $tokenResult->token;
        return response()->json([
            'message' => 'Successfully created student!',
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
            'enrollment_code' => 'required|string',
            'nickname' => 'required|string',
            'remember_me' => 'boolean'
        ]);

        if (Student::where('enrollment_code', $request->enrollment_code)->count() <= 0) return response(array("message" => "Enrollment code does not exist"), 400);
        $student = Student::with('school', 'code:id,code')->where('enrollment_code', $request->enrollment_code)->first();

        if ($request->nickname == $student->nickname) {

            $tokenResult = $student->createToken('Personal Access Token', ['student']);
            $token = $tokenResult->token;

            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();

            return response(
                array(
                    "message" => "Sign In Successful",
                    "data" => [
                        "student" => $student,
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
     * Return Student
     */
    public function student(Request $request)
    {
        $student = $request->user();
        $student->school = School::select('school')->find($student->school_id);
        $student->code = Code::select('code')->find($student->code_id);
        return response()->json($student);
    }

    public function studentLevel(Request $request)
    {
        return Log::where('student_id', $request->user()->id)->max('level_id');
    }

    public function saveLog(Request $request)
    {
        return Log::create([
            'student_id' => $request->user()->id,
            'level_id' => $request->level_id,
            'block_id' => $request->block_id,
            'problem_id' => $request->problem_id,
            'state_key' => $request->block_id == 1 ? (new CodeController())->generateRandomString(3) : $request->state_key,
            'correct_questions_id' => $request->correct_questions_id == [] ? null : implode(',', $request->correct_questions_id),
            'ppm'  => $request->ppm,
            'duration'  => $request->duration,
            'appreciation'  => $request->appreciation,
        ]);
    }

    public function progress(Request $request)
    {
        return Log::where('student_id', $request->user()->id)
            ->where('level_id', $request->level_id)->orderBy('created_at', 'asc')
            ->get(['student_id', 'level_id', 'block_id', 'state_key'])->last();
    }

    public function results(Request $request)
    {
        $logs = Log::with('problem.questions')->where('level_id', $request->level_id)
            ->where('student_id', $request->user()->id)->orderBy('id', 'desc')->take(2)->get();
        $results = [
            "block_1" => 0,
            "block_2" => 0,
            "average" => 0,
        ];
        $results["ppm"] = 0;
        foreach ($logs as $log) {
            if ($log->block_id == 1) {
                $results["block_1"] = $log->correct_questions_id == null ? 0 : (count(explode(',', $log->correct_questions_id)) * $log->problem->questions[0]->value);
            } else {
                $results["block_2"] = $log->correct_questions_id == null ? 0 : (count(explode(',', $log->correct_questions_id)) * $log->problem->questions[0]->value);
            }
            $results["ppm"] +=  $log->ppm;
        }
        $results["average"] = ($results["block_1"] + $results["block_2"]) / 2;
        return $results;
    }
}
