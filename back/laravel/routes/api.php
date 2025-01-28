<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatorController;
use App\Http\Controllers\ProjectController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/projects', [ProjectController::class, 'index']);       
    Route::get('/projects/{id}', [ProjectController::class, 'show']);   
    Route::post('/projects', [ProjectController::class, 'store']);      
    Route::put('/projects/{id}', [ProjectController::class, 'update']); 
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
});

Route::post('/register', [AuthenticatorController::class, 'register']);
Route::post('/login', [AuthenticatorController::class, 'authenticate']);    
Route::middleware('auth:sanctum')->get('/user', [AuthenticatorController::class, 'getUser']);
Route::middleware('auth:sanctum')->post('/user/avatar', [AuthenticatorController::class, 'updateAvatar']);
