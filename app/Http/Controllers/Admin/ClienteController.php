<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditarClienteRequest;
use App\Http\Requests\Admin\RegistrarClienteRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = User::orderBy('name')->get();
        return view('admin.clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('admin.clientes.create');
    }

    public function store(RegistrarClienteRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('admin.clientes')
            ->with('success', 'Usuario registrado correctamente.');
    }

    public function edit(int $id)
    {
        $cliente = User::findOrFail($id);
        return view('admin.clientes.edit', compact('cliente'));
    }

    public function update(EditarClienteRequest $request, int $id)
    {
        $cliente = User::findOrFail($id);
        $cliente->update($request->validated());

        return redirect()->route('admin.clientes')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    /**
     * Alterna entre activo ↔ inactivo.
     */
    public function toggleEstado(int $id)
    {
        $cliente = User::findOrFail($id);
        $nuevoEstado = $cliente->estado === 'activo' ? 'inactivo' : 'activo';
        $cliente->update(['estado' => $nuevoEstado]);

        $msg = $nuevoEstado === 'inactivo' ? 'Usuario desactivado.' : 'Usuario activado.';
        return redirect()->route('admin.clientes')->with('success', $msg);
    }
}
