<?php

use App\Http\Controllers\CodeController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;


// Code -> Students
Route::post('/code', [CodeController::class, 'matchStudents']);
Route::get('/code/{code}', [CodeController::class, 'invitedStudents']);

Route::post('/student/register', [StudentController::class, 'register']);
Route::post('/student/max_level', [StudentController::class, 'studentLevel']);

Route::get('/schools', [SchoolController::class, 'getSchools']);

// Question
Route::get('/problem/{level_id}/{block_id}', [ProblemController::class, 'getRandomProblem']);

// Log
Route::post('/student/log', [StudentController::class, 'saveLog']);
Route::post('/student/progress', [StudentController::class, 'progress']);

// Result
Route::post('/student/results', [StudentController::class, 'results']);

// Reports
Route::get('/student', [TeacherController::class, 'allStudents']);
Route::get('/rooms', [TeacherController::class, 'allRooms']);
Route::get('/student/{id}', [TeacherController::class, 'studentInfo']);
Route::get('/rooms/{id}', [TeacherController::class, 'roomInfo']);
