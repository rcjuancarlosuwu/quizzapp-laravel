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
        ]);
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

    public function roomInfo($id)
    {
        $code = Code::find($id);
        $code['enrollment_codes'] = explode(',', $code->enrollment_codes);
        return $code;
    }

    public function updateRoom(Request $request)
    {
        $code = Code::find($request->id);
        $code->description = $request->description;
        $code->enrollment_codes = implode(',', $request->enrollment_codes);
        $code->save();
        return $code;
    }
}
