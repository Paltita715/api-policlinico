<?php

use App\Http\Controllers\FarmacosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicacionesController;
use App\Http\Controllers\CarruselImagenesController;
use App\Http\Controllers\UserController;
use Illuminate\Session\Middleware\StartSession;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

Route::get('farmacos', [FarmacosController::class, 'index'])->name('farmacos.index');
Route::get('farmacos/{id}', [FarmacosController::class, 'show'])->name('farmacos.show');

Route::get('carruselimagenes', [CarruselImagenesController::class, 'index']);
Route::get('carruselimagenes/{id}', [CarruselImagenesController::class, 'show']);
Route::post('carruselimagenes', [CarruselImagenesController::class, 'store']);
Route::put('carruselimagenesupdate/{id}', [CarruselImagenesController::class, 'update']);
Route::delete('carruselimagenesdelete/{id}', [CarruselImagenesController::class, 'destroy']);

Route::get('publicaciones', [PublicacionesController::class, 'index']);
Route::get('publicaciones/{id}', [PublicacionesController::class, 'show']);
Route::post('publicaciones', [PublicacionesController::class, 'store']);
Route::put('publicacionesupdate/{id}', [PublicacionesController::class, 'update']);
Route::delete('publicacionesdelete/{id}', [PublicacionesController::class, 'destroy']);

// Para la autenticación en la página de publicaciones
Route::middleware(StartSession::class)->post('auth-posts', [UserController::class, 'authenticate']);
Route::middleware(StartSession::class)->get('session-validation', [UserController::class, 'checkSession']);
Route::middleware(StartSession::class)->get('logout', [UserController::class, 'logout']);
Route::post('new-user', [UserController::class, 'create']);