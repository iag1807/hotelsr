<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Habitacion;
use App\Models\Reserva;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hoy  = now()->toDateString();

        // ── Reservas de hoy ───────────────────────────────────────────────────
        $reservasHoy = Reserva::with(['user', 'habitacion'])
            ->where('fecha_ingreso', $hoy)
            ->orderByDesc('created_at')
            ->get();

        // ── Mapa de habitaciones ──────────────────────────────────────────────
        $habitaciones = Habitacion::orderByRaw("
            CASE tipo_habitacion
                WHEN 'sencilla'  THEN 1
                WHEN 'bañera'    THEN 2
                WHEN 'jacuzzi'   THEN 3
                WHEN 'doble'     THEN 4
                WHEN 'triple'    THEN 5
                WHEN 'multiple'  THEN 6
            END, numero
        ")->get();

        // IDs de habitaciones con reserva confirmada activa hoy
        $habsOcupadas = Reserva::where('estado', 'confirmada')
            ->where('fecha_ingreso', '<=', $hoy)
            ->where('fecha_salida',  '>',  $hoy)
            ->pluck('habitacion_id')
            ->flip()
            ->toArray();

        // Agrupar por tipo
        $ordenTipos  = ['sencilla', 'bañera', 'jacuzzi', 'doble', 'triple', 'multiple'];
        $habsPorTipo = $habitaciones->groupBy('tipo_habitacion');

        return view('admin.dashboard', compact(
            'user',
            'reservasHoy',
            'habsPorTipo',
            'habsOcupadas',
            'ordenTipos'
        ));
    }
}
