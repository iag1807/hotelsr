<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditarHabitacionRequest;
use App\Http\Requests\Admin\RegistrarHabitacionRequest;
use App\Models\Habitacion;

class HabitacionController extends Controller
{
    public function index()
    {
        $habitaciones = Habitacion::orderByRaw("
            CASE tipo_habitacion
                WHEN 'sencilla'  THEN 1 WHEN 'bañera'   THEN 2
                WHEN 'jacuzzi'   THEN 3 WHEN 'doble'    THEN 4
                WHEN 'triple'    THEN 5 WHEN 'multiple' THEN 6
            END, numero
        ")->get();

        return view('admin.habitaciones.index', compact('habitaciones'));
    }

    public function create()
    {
        return view('admin.habitaciones.create');
    }

    public function store(RegistrarHabitacionRequest $request)
    {
        Habitacion::create($request->validated());

        return redirect()->route('admin.habitaciones')
            ->with('success', 'Habitación registrada correctamente.');
    }

    public function edit(int $id)
    {
        $habitacion = Habitacion::findOrFail($id);
        return view('admin.habitaciones.edit', compact('habitacion'));
    }

    public function update(EditarHabitacionRequest $request, int $id)
    {
        $habitacion = Habitacion::findOrFail($id);
        $habitacion->update($request->validated());

        return redirect()->route('admin.habitaciones')
            ->with('success', 'Habitación actualizada correctamente.');
    }

    /**
     * Alterna entre disponible ↔ mantenimiento.
     */
    public function toggleEstado(int $id)
    {
        $habitacion = Habitacion::findOrFail($id);
        $nuevoEstado = $habitacion->estado === 'disponible' ? 'mantenimiento' : 'disponible';
        $habitacion->update(['estado' => $nuevoEstado]);

        $msg = $nuevoEstado === 'mantenimiento'
            ? 'Habitación puesta en mantenimiento.'
            : 'Habitación marcada como disponible.';

        return redirect()->route('admin.habitaciones')->with('success', $msg);
    }
}
