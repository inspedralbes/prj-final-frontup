<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatorController;

Route::post('/register', [AuthenticatorController::class, 'register']);
Route::post('/login', [AuthenticatorController::class, 'authenticate']);    
Route::middleware('auth:sanctum')->get('/user', [AuthenticatorController::class, 'getUser']);
