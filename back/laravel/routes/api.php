<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\Api\PreguntaController;

Route::post('/projects', [ProjectController::class, 'store']);
Route::post('/register', [AuthenticatorController::class, 'register']);
Route::post('/login', [AuthenticatorController::class, 'authenticate']);    
Route::middleware('auth:sanctum')->get('/user', [AuthenticatorController::class, 'getUser']);

Route::get('/preguntas/{nivelLenguage}/{nivelId}', [PreguntaController::class, 'getPreguntaPorNivel']);