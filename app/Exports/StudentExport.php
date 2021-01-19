<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class StudentExport implements FromView
{
    public function view(): View
    {
        return view('exports.students', [
            'students' => Student::all()
        ]);
    }
}
