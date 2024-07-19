<?php

namespace App\Http\Controllers;

use App\Models\Farmacos;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

/**
 * TODO: 
 * 1. Hacer que esta interfaz recupere datos de fármacos de la base de 
 *    datos del policlínico
 * 
 * 2. Estos métodos retornan los resultados de las consultas de todas maneras
 *    incluso si se retorna un 404, esto por precaución debido a que, al momento de
 *    hacer estas modificaciones, la frontend está escrita de tal manera que verifique
 *    si estos elementos retornan resultados vacíos o no.
 * 
 *    Cambiar los JsonResponse para que retornen mensajes en caso de 404 podría romper
 *    la frontend. Si se desea que los JsonResponse retornen mensajes vez de objetos y
 *    arrays vacíos es necesario hacer los cambios respectivos en la frontend.
 *    (esto va para index(), show() si tiene estas modificaciones debido a no es 
 *    actualmente usado por la frontend).
 */
class FarmacosController extends Controller
{
    /**
     * Retorna todas los fármacos de la base de datos
     * 
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 404: no existen fármacos en la base de datos
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
     */
    public function index()
    {
        //
        try{
            $resultado = Farmacos::all();
            if($resultado === null)
                return new JsonResponse($resultado, 404);

            return $resultado;
        }
        catch (\Exception $e){
            return new JsonResponse([
                'message' => 'Error interno del servidor'
            ], 500);
        }
    }

    /**
     * Retorna el fármaco que tenga asignado el ID recibido
     * 
     * @param string $id
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 404: no existe una instancia de fármaco que corresponda con el ID recibido
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
     */
    public function show(string $id)
    {
        try{
            $farmaco = DB::table('farmacos')->where('id_farmaco', intval($id))->first();
            if($farmaco === null)
                return new JsonResponse([
                    'message' => 'el fármaco no existe'
                ], 404);

            return $farmaco;
        }
        catch (\Exception $e){
            return new JsonResponse([
                'message' => "Error interno del servidor"
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage. (don't need it)
     */
    /*public function update(Request $request, string $id)
    {
        //
    }*/

    /**
     * Remove the specified resource from storage. (why would I, I told you I just 
     * wanna fetch stuff)
     */
    /*public function destroy(string $id)
    {
        //
    }*/

    

    /**
     * Store a newly created resource in storage. (don't need it, i'm just gonna fetch
     * stuff from a json)
     */
    /*public function store(Request $request)
    {
        //
    }*/
}