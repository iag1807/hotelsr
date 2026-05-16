<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsCliente
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->rol !== 'cliente') {
            abort(403, 'Acceso restringido a clientes.');
        }

        if ($user->estado !== 'activo') {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['cuenta' => 'Tu cuenta está inactiva. Contacta al hotel.']);
        }

        return $next($request);
    }
}
