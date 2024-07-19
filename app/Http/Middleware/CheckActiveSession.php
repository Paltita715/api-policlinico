<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckActiveSession
{

    /**
     * Este middleware revisa si el que envió una solicitud a los endpoints del servidor tiene una
     * sesión activa, si existe, ejecuta la función del endpoint o el siguiente middleware. Si no, 
     * retorna 401.
     * 
     * Con esta middleware se puede verificar la sesión después de la comprobación inicial en la
     * página de login
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response | JsonResponse
    {
        try{
            if(Auth::check()){
                return $next($request);
            }

            return new JsonResponse([
                'sessionActive' => 0,
                'message' => 'Se ha intentado acceder con un usuario no autenticado'
            ], 401);
        }
        catch(Exception $ex){
            return new JsonResponse([
                'sessionActive' => 0,
                'message' => "Error interno del servidor {$ex->getMessage()}"
            ], 500);
        }
    }
}
