<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;



Route::post('projects', [ProjectController::class, 'store']);
