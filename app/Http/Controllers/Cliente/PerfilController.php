<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\ActualizarPerfilRequest;
use App\Http\Requests\Cliente\CambiarContrasenaRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    // ── Vista: perfil ─────────────────────────────────────────────────────────

    public function index()
    {
        $user = auth()->user();
        return view('cliente.perfil', compact('user'));
    }

    // ── Actualizar datos personales ───────────────────────────────────────────

    public function actualizar(ActualizarPerfilRequest $request): RedirectResponse
    {
        $user = auth()->user();

        // Solo los campos editables: name, email, celular
        // documento, tipo_documento, genero, rol, estado → no editables por el cliente
        $user->update($request->validated());

        return redirect()->route('cliente.perfil')
            ->with('success', 'Datos actualizados correctamente.');
    }

    // ── Cambiar contraseña ────────────────────────────────────────────────────

    public function cambiarContrasena(CambiarContrasenaRequest $request): RedirectResponse
    {
        $user = auth()->user();

        if (!Hash::check($request->actual, $user->password)) {
            return back()
                ->withErrors(['actual' => 'La contraseña actual no es correcta.'])
                ->withInput();
        }

        $user->update([
            'password' => Hash::make($request->nueva),
        ]);

        return redirect()->route('cliente.perfil')
            ->with('success', 'Contraseña actualizada correctamente.');
    }
}
