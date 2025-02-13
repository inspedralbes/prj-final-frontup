<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;


Route::get('/', function () {
    return view('home');
})->name('home');

Route::resource('users', UserController::class);

Route::resource('projects', ProjectController::class);
