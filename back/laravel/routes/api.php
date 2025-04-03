<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PreguntaController;
use App\Http\Controllers\LikesController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'getUser']);
    Route::post('/user/avatar', [UserController::class, 'updateAvatar']);
    //proyectos del usuario solo
    Route::get('/projects', [ProjectController::class, 'index']);

    //dar like
    Route::post('/likes', [LikesController::class, 'store']);
    //borrar like
    Route::delete('/likes/{projectId}', [LikesController::class, 'destroy']);
    //todos los likes que has dado
    Route::get('/likes/check/{projectId}', [LikesController::class, 'checkLike']);
    //chequea si le has dado like a una publicacion
    Route::get('/likes/user', [LikesController::class, 'userLikes']);
});
//contador de likes para un proyecto
Route::get('/likes/count/{projectId}', [LikesController::class, 'likeCount']);
//indexa proyectos de todos los usuarios
Route::get('/projects/all', [ProjectController::class, 'indexAll']); 
Route::post('/projects', [ProjectController::class, 'store']);      
Route::get('/projects/{id}', [ProjectController::class, 'show']);   
Route::put('/projects/{id}', [ProjectController::class, 'update']); 
Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
Route::get('/projectsAllPaginado', [ProjectController::class, 'index']);


Route::post('/register', [AuthenticatorController::class, 'register']);

Route::post('/login', [AuthenticatorController::class, 'authenticate']);    

Route::get('/preguntas/{language}/{id}', [PreguntaController::class, 'getPreguntas']);

Route::post('/actualizar-nivel', [PreguntaController::class, 'actualizarNivel']);