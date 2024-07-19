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
     * Muestra todos los elementos del carrusel de imágenes
     * 
     * @return JsonResponse con un estado de 200: ok
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
     * Guarda información de una imagen del carrusel en una base de datos. La imagen es
     * guardada en el almacenamiento del servidor
     * 
     * @param CarruselImagenesStoreRequest $request
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
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
     * Retorna un elemento carrusel del servidor según un ID especificado
     * 
     * @param mixed $id
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 404: el ID recibido no corresponde con ninguna instancia de carrusel actual 
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
     * Show the form for editing the specified resource. (no usado)
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Modificar una entrada del carrusel de imágenes según el Id especificado
     * 
     * @param CarruselImagenesStoreRequest $request
     * @param mixed $id
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 404: el ID recibido no corresponde con ninguna instancia de carrusel actual 
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
     */
    public function update(CarruselImagenesStoreRequest $request, $id)
    {
        try{
            // Consulta si existe el recurso y actualiza el texto alternativo
            $carruselImagen = CarruselImagenes::find($id);
            if(!$carruselImagen) {
                return response()->json([
                    'message' => 'Imagen no encontrada'
                ],404);
            }
            $carruselImagen->alt = $request->alt;

            // Consulta si $request contiene una imagen y la usa para reemplazar
            // la anterior, o añadir si la instancia consultada no contiene
            // una imágen (por algún motivo)
            if($request->imagen){
                $storage = Storage::disk('public');
                if($storage->exists($carruselImagen->imagen)){
                    $storage->delete($carruselImagen->imagen);
                }
                $imagenName = Str::random(32).".".$request->imagen->getClientOriginalExtension();
                $carruselImagen->imagen = $imagenName;
                $storage->put($imagenName, file_get_contents($request->imagen));
            }

            // Guardar cambios en la base de datos y retornar mensaje
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
     * Elimina la instancia especificada por el ID de la base de datos y su imagen
     * vinculada del almacenamiento del servidor
     * 
     * @param mixed $id
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 404: el ID recibido no corresponde con ninguna instancia de carrusel actual 
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
