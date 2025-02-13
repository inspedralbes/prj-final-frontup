<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PreguntaController;
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user/avatar', [UserController::class, 'updateAvatar']);

});
Route::post('/projects', [ProjectController::class, 'store']);      
Route::get('/projects', [ProjectController::class, 'index']);       
Route::get('/projects/{id}', [ProjectController::class, 'show']);   
Route::put('/projects/{id}', [ProjectController::class, 'update']); 
Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);

Route::post('/register', [AuthenticatorController::class, 'register']);

Route::post('/login', [AuthenticatorController::class, 'authenticate']);    


Route::get('/preguntas/{language}/{id}', [PreguntaController::class, 'getPreguntas']);

Route::post('/actualizar-nivel', [PreguntaController::class, 'actualizarNivel']);