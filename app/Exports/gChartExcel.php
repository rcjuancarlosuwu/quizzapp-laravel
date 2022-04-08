<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;

class gChartExcel implements FromView
{
    private $code_id;
    public function __construct($code_id)
    {
        $this->code_id = $code_id;
    }

    public function view(): View
    {
        $students = Student::with('logs.problem.questions')->where('code_id', $this->code_id)->orderBy('enrollment_code')->get();

        return view('exports.students-gchart', [
            'students' => $students,
        ]);
    }
}
