<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CarruselImagenes;
use App\Http\Requests\CarruselImagenesStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CarruselImagenesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $imagenes = CarruselImagenes::all();

        return response()->json([
            'imagenes' => $imagenes
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CarruselImagenesStoreRequest $request)
    {
        try{
            $imagenName = Str::random(32).".".$request->imagen->getClientOriginalExtension();
            CarruselImagenes::create([
                'imagen' => $imagenName,
                'alt' => $request->alt
            ]);
            Storage::disk('public')->put($imagenName, file_get_contents($request->imagen));
            return response()->json([
                'message' => 'Imagen creada correctamente'
            ],200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ],500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $carruselImagen = CarruselImagenes::find($id);
        if(!$carruselImagen) {
            return response()->json([
                'message' => 'Imagen no encontrada'
            ],404);
        }

        return response()->json([
            'imagen' => $carruselImagen
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CarruselImagenesStoreRequest $request, $id)
    {
        try{
            $carruselImagen = CarruselImagenes::find($id);
            if(!$carruselImagen) {
                return response()->json([
                    'message' => 'Imagen no encontrada'
                ],404);
            }
            $carruselImagen->alt = $request->alt;
            if($request->imagen){
                $storage = Storage::disk('public');
                if($storage->exists($carruselImagen->imagen)){
                    $storage->delete($carruselImagen->imagen);
                }
                $imagenName = Str::random(32).".".$request->imagen->getClientOriginalExtension();
                $carruselImagen->imagen = $imagenName;
                $storage->put($imagenName, file_get_contents($request->imagen));
            }
            $carruselImagen->save();
            return response()->json([
                'message' => 'Imagen actualizada correctamente'
            ],200);
        } catch(\Exception $e) {
            return response()->json([
                'message' => 'Algo ah ocurrido mal :('
            ],500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $carruselImagen = CarruselImagenes::find($id);
        if(!$carruselImagen){
            return response()->json([
                'message' => 'Imagen no encontrada'
            ],404);
        }
        $storage = Storage::disk('public');
        if($storage->exists($carruselImagen->imagen)){
            $storage->delete($carruselImagen->imagen);
        }
        $carruselImagen->delete();
        return response()->json([
            'message' => 'Imagen eliminada correctamente'
        ],200);
    }
}
