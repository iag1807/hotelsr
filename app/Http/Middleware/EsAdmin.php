<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user || $user->rol !== 'admin') {
            abort(403, 'Acceso restringido a administradores.');
        }

        if ($user->estado !== 'activo') {
            auth()->logout();
            return redirect()->route('login')
                ->withErrors(['cuenta' => 'Tu cuenta está inactiva.']);
        }

        return $next($request);
    }
}
