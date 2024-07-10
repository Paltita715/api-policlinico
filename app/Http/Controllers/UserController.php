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

    // adds a new user into the user's database
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

    // authenticates a user
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
