<?php

use App\Http\Controllers\FarmacosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicacionesController;
use App\Http\Controllers\CarruselImagenesController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CheckActiveSession;
use Illuminate\Session\Middleware\StartSession;

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');*/

// El middleware HandleCors está actualmente colocado en bootstrap/app.php

// Todas las rutas que requieran una sesión (o generen una) estáran indicadas
// con una '->S' en los comentarios

/**
 * Rutas utilizadas para obtener datos de los fármacos
 */
Route::get('farmacos', [FarmacosController::class, 'index'])->name('farmacos.index'); // Consulta
Route::get('farmacos/{id}', [FarmacosController::class, 'show'])->name('farmacos.show'); // Consulta según ID

// Rutas utilizadas para la administración de las imágenes en el carrusel
Route::get('carruselimagenes', [CarruselImagenesController::class, 'index']); // Consulta
Route::get('carruselimagenes/{id}', [CarruselImagenesController::class, 'show']); // Consulta según ID

// Creación->S
Route::middleware([StartSession::class, CheckActiveSession::class])
                ->post('carruselimagenes', [CarruselImagenesController::class, 'store']);

// Actualización->S
Route::middleware([StartSession::class, CheckActiveSession::class])
                ->put('carruselimagenesupdate/{id}', [CarruselImagenesController::class, 'update']);

// Eliminación->S
Route::middleware([StartSession::class, CheckActiveSession::class])
                ->delete('carruselimagenesdelete/{id}', [CarruselImagenesController::class, 'destroy']);

// Rutas utilizadas para la administración de publicaciones
Route::get('publicaciones', [PublicacionesController::class, 'index']); // Consulta
Route::get('publicaciones/{id}', [PublicacionesController::class, 'show']); // Consulta según ID

// Creación->S
Route::middleware([StartSession::class, CheckActiveSession::class])
                ->post('publicaciones', [PublicacionesController::class, 'store']);

// Actualización->S
Route::middleware([StartSession::class, CheckActiveSession::class])
                ->put('publicacionesupdate/{id}', [PublicacionesController::class, 'update']);

// Eliminación->S
Route::middleware([StartSession::class, CheckActiveSession::class])
                ->delete('publicacionesdelete/{id}', [PublicacionesController::class, 'destroy']);

// Para la autenticación en la página de publicaciones
Route::middleware(StartSession::class)->post('auth-posts', [UserController::class, 'authenticate']); // Inicio de sesión->S
Route::middleware(StartSession::class)->get('session-validation', [UserController::class, 'checkSession']); // Verificación de sesión->S
Route::middleware(StartSession::class)->get('logout', [UserController::class, 'logout']); // Cierre de sesión->S
Route::post('new-user', [UserController::class, 'create']);  // Registro