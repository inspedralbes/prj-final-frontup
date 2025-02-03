<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProjectController;


Route::get('/', [UserController::class, 'index'])->name('users.index'); // Página principal (lista de usuarios)
Route::get('create', [UserController::class, 'create'])->name('users.create'); // Crear nuevo usuario
Route::post('store', [UserController::class, 'store'])->name('users.store'); // Guardar nuevo usuario
Route::get('{user}/edit', [UserController::class, 'edit'])->name('users.edit'); // Editar usuario
Route::put('{user}', [UserController::class, 'update'])->name('users.update'); // Actualizar usuario
Route::delete('{user}', [UserController::class, 'destroy'])->name('users.destroy'); // Eliminar usuario


Route::resource('projects', ProjectController::class);
