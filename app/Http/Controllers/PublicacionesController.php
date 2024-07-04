<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicaciones;
use App\Http\Requests\PublicacionesStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class PublicacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publicaciones = Publicaciones::all();

        return response()->json([
            'publicaciones' => $publicaciones
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
    public function store(PublicacionesStoreRequest $request)
    {
        try{
            $imagenName = Str::random(32).".".$request->imagen->getClientOriginalExtension();
            Publicaciones::create([
                'titulo' => $request->titulo,
                'imagen' => $imagenName,
                'contenido' => $request->contenido
            ]);
            Storage::disk('public')->put($imagenName, file_get_contents($request->imagen));
            return response()->json([
                'message' => 'Publicacion creada correctamente'
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
        $publicacion = Publicaciones::find($id);
        if(!$publicacion) {
            return response()->json([
                'message' => 'Publicacion no encontrada'
            ],404);
        }

        return response()->json([
            'publicacion' => $publicacion
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
    public function update(PublicacionesStoreRequest $request, $id)
    {
        try{
            $publicacion = Publicaciones::find($id);
            if(!$publicacion) {
                return response()->json([
                    'message' => 'Publicacion no encontrada'
                ],404);
            }
            $publicacion->titulo = $request->titulo;
            $publicacion->contenido = $request->contenido;
            if($request->imagen){
                $storage = Storage::disk('public');
                if($storage->exists($publicacion->imagen)){
                    $storage->delete($publicacion->imagen);
                }
                $imagenName = Str::random(32).".".$request->imagen->getClientOriginalExtension();
                $publicacion->imagen = $imagenName;
                $storage->put($imagenName, file_get_contents($request->imagen));
            }
            $publicacion->save();
            return response()->json([
                'message' => 'Publicacion actualizada correctamente'
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
        $publicacion = Publicaciones::find($id);
        if(!$publicacion){
            return response()->json([
                'message' => 'Publicacion no encontrada'
            ],404);
        }
        $storage = Storage::disk('public');
        if($storage->exists($publicacion->imagen)){
            $storage->delete($publicacion->imagen);
        }
        $publicacion->delete();
        return response()->json([
            'message' => 'Publicacion eliminada correctamente'
        ],200);
    }
}
