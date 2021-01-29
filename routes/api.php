<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CodeController;
use App\Http\Controllers\ProblemController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;



Route::group(['prefix' => 'v1'], function () {

    // No auth require
    Route::get('schools', [SchoolController::class, 'getSchools']);

    Route::group(['prefix' => 'student'], function () {

        // no auth
        Route::post('signup', [StudentController::class, 'signup']);
        Route::post('login', [StudentController::class, 'login']);
        Route::get('room/{code}', [CodeController::class, 'invitedStudents']);

        Route::group(['middleware' => ['auth:student', 'scopes:student']], function () {
            Route::get('info', [StudentController::class, 'student']);
            Route::post('log', [StudentController::class, 'saveLog']);
            Route::post('progress', [StudentController::class, 'progress']);
            Route::post('results', [StudentController::class, 'results']);

            Route::get('logout', [AuthController::class, 'logout']);
            Route::get('max_level', [StudentController::class, 'studentLevel']);
            Route::get('problem/{level_id}/{block_id}', [ProblemController::class, 'getRandomProblem']);
        });
    });

    Route::group(['prefix' => 'teacher'], function () {

        // no auth
        Route::post('signup', [TeacherController::class, 'signup']);
        Route::post('login', [TeacherController::class, 'login']);

        Route::group(['middleware' => ['auth:teacher', 'scopes:teacher']], function () {
            Route::get('info', [TeacherController::class, 'teacher']);
            Route::get('logout', [AuthController::class, 'logout']);

            Route::get('student', [TeacherController::class, 'allStudents']);
            Route::get('student/{id}', [TeacherController::class, 'studentInfo']);

            Route::post('room', [CodeController::class, 'matchStudents']);
            Route::get('room/{id}', [CodeController::class, 'roomInfo']);
            Route::put('room/update', [CodeController::class, 'updateRoom']);
            Route::get('rooms', [TeacherController::class, 'allRooms']);

            Route::get('student/excel', [TeacherController::class, 'allStudentsExcel']);

            Route::get('problems/{state_key}', [ProblemController::class, 'problemsByStateKey']);
        });
    });
});
