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
     * Handle an incoming request.
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
