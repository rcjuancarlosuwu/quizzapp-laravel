<?php

namespace App\Http\Controllers;

use App\Models\Code;
use Illuminate\Http\Request;

class CodeController extends Controller
{
    public function matchStudents(Request $request)
    {
        return Code::create([
            'code' => $this->generateRandomString(5),
            'description' => $request->description,
            'enrollment_codes' => implode(',', $request->enrollment_codes)
        ])->code;
    }

    function generateRandomString($length)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function invitedStudents($code)
    {
        return explode(',', Code::select('enrollment_codes')->where('code', $code)->first()->enrollment_codes);
    }
}
