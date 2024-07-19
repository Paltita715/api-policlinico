<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{

    /**
     * Crea un nuevo usuario
     * 
     * @param Request $request
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
     */
    public function create(Request $request){
        try{
            if(isset($request->name, $request->password)){
                $nuevoUsuario = User::factory()->create([
                    'username' => $request->name,
                    'password' => bcrypt($request->password)
                ]);

                return new JsonResponse([
                    'message' => 'Operación realizada exitosamente'
                ], 200);
            }

            return new JsonResponse([
                'message' => 'Datos inválidos'
            ], 400);
        } catch (\Exception $ex) {
            return new JsonResponse([
                'message' => "Error interno del servidor ({$ex->getMessage()})"
            ], 500);
        }
    }

    /**
     * Autentica un usuario verificando la existencia de los datos de inicio de sesión
     * recibidos en la base de datos
     * 
     * @param Request $request
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 401: Inicio de sesión erróneo
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
     */
    public function authenticate(Request $request){
        try
        {
            if(isset($request->name, $request->password) === false){
                return new JsonResponse([
                    'loggedIn' => 0,
                    'message' => 'Credenciales inválidas'
                ], 401);
            }

            $credenciales = [
                'username' => $request->name,
                'password' => $request->password
            ];

            if(Auth::attempt($credenciales)) {
                $request->session()->regenerate();

                $respuesta = new JsonResponse([
                    'loggedIn' => 1,
                    'message' => null
                ], 200);

                // Las cookies parecen que son enviadas de manera automática
                return $respuesta;
            }

            return new JsonResponse([
                'loggedIn' => 0,
                'message' => 'Credenciales inválidas'
            ], 401);
        }
        catch(\Exception $ex){
            return new JsonResponse([
                'loggedIn' => 0,
                'message' => "Error interno del servidor ({$ex->getMessage()})"
            ], 500);
        }
    }

    /**
     * Verifica la existencia de una sesión activa revisando la existencia de un token de
     * inicio de sesión válido en la solicitud hecha al endpoint que tiene asignado esta
     * función
     * 
     * @param Request $request
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 401: Inicio de sesión erróneo
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
     */
    public function checkSession(Request $request){
        try{
            if(Auth::check()){
                return new JsonResponse([
                    'sessionActive' => 1,
                    'message' => null
                ], 200);
            }

            return new JsonResponse([
                'sessionActive' => 0,
                'message' => null
            ], 401);
        }
        catch(\Exception $e){
            return new JsonResponse([
                'loggedIn' => 0,
                'message' => "Error interno del servidor ({$e->getMessage()})"
            ], 500);
        }
    }

    /**
     * Cierra la sesión actual e invalida el token de inicio de sesión del la sesión
     * (valga la redundancia) previamente activa.
     * 
     * @param Request $request
     * @return JsonResponse con los siguientes estados posibles
     * 200: ok
     * 500: error interno del servidor si alguna excepción ocurre al 
     * tratar de realizar la solicitud
     */
    public function logout(Request $request){
        try{
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return new JsonResponse([
                'message' => null
            ], 200);
        }
        catch(\Exception $e){
            return new JsonResponse([
                'loggedIn' => 0,
                'message' => "Error interno del servidor ({$e->getMessage()})"
            ], 500);
        }
    }
}
