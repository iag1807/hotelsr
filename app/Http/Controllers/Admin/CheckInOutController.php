<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CheckIn;
use App\Models\CheckOut;
use App\Models\Reserva;
use Illuminate\Support\Facades\DB;

class CheckInOutController extends Controller
{
    // ── Vista: listado de reservas confirmadas ────────────────────────────────

    public function index()
    {
        $reservas = Reserva::with(['user', 'habitacion', 'checkIn'])
            ->where('estado', 'confirmada')
            ->orderBy('fecha_ingreso')
            ->get();

        return view('admin.checks.index', compact('reservas'));
    }

    // ── Registrar Check-in ────────────────────────────────────────────────────

    public function checkIn(int $idReserva)
    {
        $reserva = Reserva::where('id', $idReserva)
            ->where('estado', 'confirmada')
            ->firstOrFail();

        if ($reserva->checkIn) {
            return redirect()->route('admin.checks')
                ->with('error', 'Esta reserva ya tiene un check-in registrado.');
        }

        CheckIn::create([
            'reserva_id'          => $reserva->id,
            'registrado_por'      => auth()->id(),
            'fecha_hora_check_in' => now(),
            'numero_personas'     => $reserva->numero_personas,
            'observaciones'       => null,
        ]);

        return redirect()->route('admin.checks')
            ->with('success', 'Check-in registrado correctamente.');
    }

    // ── Registrar Check-out ───────────────────────────────────────────────────

    public function checkOut(int $idReserva)
    {
        $reserva = Reserva::where('id', $idReserva)
            ->where('estado', 'confirmada')
            ->firstOrFail();

        if (!$reserva->checkIn) {
            return redirect()->route('admin.checks')
                ->with('error', 'No se puede registrar el check-out sin un check-in previo.');
        }

        if ($reserva->checkOut) {
            return redirect()->route('admin.checks')
                ->with('error', 'Esta reserva ya tiene un check-out registrado.');
        }

        try {
            DB::transaction(function () use ($reserva) {
                CheckOut::create([
                    'reserva_id'           => $reserva->id,
                    'registrado_por'       => auth()->id(),
                    'fecha_hora_check_out' => now(),
                    'numero_personas'      => $reserva->numero_personas,
                    'observaciones'        => null,
                ]);

                $reserva->update(['estado' => 'finalizada']);
            });

            return redirect()->route('admin.checks')
                ->with('success', 'Check-out registrado. La reserva ha sido finalizada.');

        } catch (\Throwable $e) {
            return redirect()->route('admin.checks')
                ->with('error', 'Error al registrar el check-out. Intenta de nuevo.');
        }
    }
}
