<?php

use App\Http\Controllers\FarmacosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PublicacionesController;
use App\Http\Controllers\CarruselImagenesController;

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