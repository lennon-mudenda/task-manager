<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProjectController;

Route::get('/', function () {
    return view('welcome');
});

Route::apiResource('tasks', TaskController::class);
Route::apiResource('projects', ProjectController::class);
