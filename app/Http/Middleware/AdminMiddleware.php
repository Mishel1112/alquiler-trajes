<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->esAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Acceso restringido solo para administradores.'], 403);
            }
            return redirect()->route('dashboard')->with('error', 'Acceso restringido: Esta sección solo está disponible para el Administrador.');
        }

        return $next($request);
    }
}
