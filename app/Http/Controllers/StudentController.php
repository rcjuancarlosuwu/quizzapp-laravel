<?php

namespace App\Http\Controllers;

use App\Models\Attempts;
use App\Models\Code;
use App\Models\Log;
use App\Models\Question;
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

        Attempts::create([
            'student_id' => $student->id,
            'attempt' => 1,
            'xp' => 0
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
        $student->attempts = Attempts::where('student_id', $student->id)->get(['attempt', 'xp']);
        return response()->json($student);
    }

    public function studentLevel(Request $request)
    {
        $log = Log::where('attempt_id', $this->currentAttempt($request->user()->id)->id)->where('student_id', $request->user()->id)->get();
        return [
            'max_level' => $log->max('level_id') ?? 1,
            'xp1' => $log->where('level_id', 1)->sum('score'),
            'xp2' => $log->where('level_id', 2)->sum('score'),
            'xp3' => $log->where('level_id', 3)->sum('score')
        ];
    }

    public function saveLog(Request $request)
    {
        // experience points
        $point_value = 20 / Question::where('problem_id', $request->problem_id)->count();
        $attempt = $this->currentAttempt($request->user()->id);
        $xp = (count($request->correct_questions_id) * $point_value) + ($request->ppm_points ?? 0);
        $attempt->xp += $xp;
        $attempt->save();

        return Log::create([
            'attempt_id' => $attempt->id,
            'student_id' => $request->user()->id,
            'level_id' => $request->level_id,
            'block_id' => $request->block_id,
            'problem_id' => $request->problem_id,
            'state_key' => $request->block_id == 1 ? (new CodeController())->generateRandomString(3) : $request->state_key,
            'correct_questions_id' => $request->correct_questions_id == [] ? null : implode(',', $request->correct_questions_id),
            'ppm'  => $request->ppm,
            'score'  => $xp,
            'ppm_points'  => $request->ppm_points,
            'duration'  => $request->duration,
            'appreciation'  => $request->appreciation,
        ]);
    }

    public function progress(Request $request)
    {

        return Log::where('attempt_id', $this->currentAttempt($request->user()->id)->id)->where('student_id', $request->user()->id)
            ->where('level_id', $request->level_id)
            ->orderBy('created_at', 'asc')
            ->get(['student_id', 'level_id', 'block_id', 'state_key'])->last();
    }

    public function results(Request $request)
    {
        $logs = Log::with('problem.questions')->where('attempt_id', $this->currentAttempt($request->user()->id)->id)->where('level_id', $request->level_id)
            ->where('student_id', $request->user()->id)->orderBy('id', 'desc')->take(2)->get();
        $results = [
            "block_1" => $logs->where('block_id', 1)->sum('score'),
            "block_2" => $logs->where('block_id', 2)->sum('score'),
            "ppm" => $logs->sum('ppm'),
            "average" => $logs->avg('score') ?? 0,
        ];
        if ($request->level_id == 3) {
            Attempts::create([
                'student_id' => $request->user()->id,
                'attempt' => Attempts::where('student_id', $request->user()->id)->max('attempt') + 1,
                'xp' => 0
            ]);
        }
        return $results;
    }

    public function currentAttempt($id)
    {
        return Attempts::where('student_id', $id)->orderBy('attempt', 'desc')->first();
    }

    public function iChart($student_id, $attempt_id)
    {
        $logs = Log::where('attempt_id', $attempt_id)->where('student_id', $student_id)->get();
        return [
            'scores' => [
                [
                    'name' => 'Nivel 1',
                    'value' => $logs->where('level_id', 1)->sum('score')
                ],
                [
                    'name' => 'Nivel 2',
                    'value' => $logs->where('level_id', 2)->sum('score')
                ],
                [
                    'name' => 'Nivel 3',
                    'value' => $logs->where('level_id', 3)->sum('score')
                ],
            ],
            'ppms' => [
                [
                    'name' => 'Nivel 1',
                    'value' => $logs->where('level_id', 1)->sum('ppm')
                ],
                [
                    'name' => 'Nivel 2',
                    'value' => $logs->where('level_id', 2)->sum('ppm')
                ],
                [
                    'name' => 'Nivel 3',
                    'value' => $logs->where('level_id', 3)->sum('ppm')
                ],
            ],
        ];
    }
}
