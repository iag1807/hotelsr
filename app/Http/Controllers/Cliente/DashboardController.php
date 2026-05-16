<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Capacidades máximas por tipo (para filtrar tarjetas según personas buscadas)
        $capacidades = Habitacion::selectRaw('tipo_habitacion, MAX(capacidad) as capacidad_max')
            ->groupBy('tipo_habitacion')
            ->pluck('capacidad_max', 'tipo_habitacion')
            ->mapWithKeys(fn($v, $k) => [strtolower($k) => (int) $v])
            ->toArray();

        $habitacionesDisponibles = [];
        $mapaHabitaciones        = [];
        $busquedaRealizada       = false;
        $entrada = $salida       = null;
        $personas                = null;

        // ── Búsqueda ──────────────────────────────────────────────────────────
        if ($request->hasAny(['entrada', 'salida', 'personas'])) {

            // Validar manualmente el request de búsqueda
            $validated = $request->validate([
                'entrada'  => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:today'],
                'salida'   => ['required', 'date', 'date_format:Y-m-d', 'after:entrada'],
                'personas' => ['required', 'integer', 'min:1', 'max:10'],
            ], [
                'entrada.after_or_equal' => 'La fecha de entrada no puede ser en el pasado.',
                'salida.after'           => 'La fecha de salida debe ser posterior a la de entrada.',
                'personas.min'           => 'Debe haber al menos 1 persona.',
            ]);

            $entrada  = $validated['entrada'];
            $salida   = $validated['salida'];
            $personas = (int) $validated['personas'];
            $busquedaRealizada = true;

            // Habitaciones disponibles: estado = 'disponible' (no 'mantenimiento')
            $habitaciones = Habitacion::disponibleParaFechas($entrada, $salida, $personas)->get();

            foreach ($habitaciones as $hab) {
                $tipo = strtolower($hab->tipo_habitacion);

                $habitacionesDisponibles[$tipo][] = [
                    'id'               => $hab->id,
                    'numero'           => $hab->numero,
                    'precio'           => (int) $hab->precio,
                    'precio_adicional' => (int) $hab->precio_persona_adicional,
                ];

                $mapaHabitaciones[$hab->id] = [
                    'tipo'             => $tipo,
                    'numero'           => $hab->numero,
                    'precio'           => (int) $hab->precio,
                    'precio_adicional' => (int) $hab->precio_persona_adicional,
                ];
            }
        }

        return view('cliente.dashboard', compact(
            'user',
            'capacidades',
            'habitacionesDisponibles',
            'mapaHabitaciones',
            'busquedaRealizada',
            'entrada',
            'salida',
            'personas'
        ));
    }
}
