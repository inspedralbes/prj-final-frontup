<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticatorController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\NivellUsuariController;
use App\Http\Controllers\NivelController;         
use App\Http\Controllers\NivelCssController;      
use App\Http\Controllers\NivelJsController; 

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
    //Todas las publicaciones a las que le ha dado like un usuario
    Route::get('/likes/allUserLikes', [LikesController::class, 'userAllLikes']);
    Route::put('/projects/{id}', [ProjectController::class, 'update']); 
    Route::apiResource('nivells_usuaris', NivellUsuariController::class)->only([
        'index', 'store', 'show', 'update', 'destroy'
    ]);
    Route::get('/nivelUsuari/{id}', [NivellUsuariController::class, 'show']);
});
//Niveles
Route::prefix('niveles')->group(function () {
    // HTML
    Route::prefix('html')->group(function () {
        Route::get('/pregunta/{id}', [NivelController::class, 'getPregunta']);
        Route::post('/verificar/{id}', [NivelController::class, 'verificarRespuesta']);
        Route::post('/actualizar', [NivelController::class, 'actualizarNivel']);
    });

    // CSS
    Route::prefix('css')->group(function () {
        Route::get('/pregunta/{id}', [NivelCssController::class, 'getPregunta']);
        Route::post('/verificar/{id}', [NivelCssController::class, 'verificarRespuesta']);
        Route::post('/actualizar', [NivelCssController::class, 'actualizarNivel']);
    });

    // JavaScript
    Route::prefix('js')->group(function () {
        Route::get('/pregunta/{id}', [NivelJsController::class, 'getPregunta']);
        Route::post('/verificar/{id}', [NivelJsController::class, 'verificarRespuesta']);
        Route::post('/actualizar', [NivelJsController::class, 'actualizarNivel']);
    });
});

//contador de likes para un proyecto
Route::get('/likes/count/{projectId}', [LikesController::class, 'likeCount']);
//indexa proyectos de todos los usuarios
Route::get('/projects/all', [ProjectController::class, 'indexAll']); 
Route::post('/projects', [ProjectController::class, 'store']);      
Route::get('/projects/{id}', [ProjectController::class, 'show']);   
Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
Route::get('/projectsAllPaginado', [ProjectController::class, 'index']);
Route::get('/projects/{id}/preview', [ProjectController::class, 'preview'])->name('projects.preview');


Route::post('/register', [AuthenticatorController::class, 'register']);

Route::post('/login', [AuthenticatorController::class, 'authenticate']);    