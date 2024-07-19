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
     * Retorna todas las publicaciones
     * 
     * @return JsonResponse
     */
    public function index()
    {
        $publicaciones = Publicaciones::all();

        return response()->json([
            'publicaciones' => $publicaciones
        ], 200);
    }

    /**
     * Show the form for creating a new resource. (no usado)
     */
    public function create()
    {
        //
    }

    /**
     * Almacena una nueva instancia de una imágen en la base de datos.
     * La imagen actual es guardada en el almacenamiento del servidor.
     * 
     * @param PublicacionesStoreRequest $request
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
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
     * Retorna una instancia de publicación del servidor según un ID especificado
     * 
     * @param mixed $id
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 404: el ID recibido no corresponde con ninguna instancia de carrusel actual 
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
     * No usado
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Modificar una entrada de las publicaciones según el ID especificado
     * 
     * @param CarruselImagenesStoreRequest $request
     * @param mixed $id
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 404: el ID recibido no corresponde con ninguna instancia de publicaciones actual 
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
     */
    public function update(PublicacionesStoreRequest $request, $id)
    {
        try{
            // Consulta según ID
            $publicacion = Publicaciones::find($id);
            if(!$publicacion) {
                return response()->json([
                    'message' => 'Publicacion no encontrada'
                ],404);
            }

            // Actualización de atributos
            $publicacion->titulo = $request->titulo;
            $publicacion->contenido = $request->contenido;

            // Reemplazo de la imagen actual en el almacenamiento por una nueva
            if($request->imagen){
                $storage = Storage::disk('public');
                if($storage->exists($publicacion->imagen)){
                    $storage->delete($publicacion->imagen);
                }
                $imagenName = Str::random(32).".".$request->imagen->getClientOriginalExtension();
                $publicacion->imagen = $imagenName;
                $storage->put($imagenName, file_get_contents($request->imagen));
            }

            // Guardado de cambios
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
     * Elimina la instancia especificada por el ID de la base de datos y su imagen
     * vinculada del almacenamiento del servidor
     * 
     * @param mixed $id
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 404: el ID recibido no corresponde con ninguna instancia de publicaciones actual 
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
