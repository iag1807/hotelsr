<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EditarReservaRequest;
use App\Http\Requests\Admin\RegistrarReservaRequest;
use App\Models\Habitacion;
use App\Models\Pago;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    // ── Listado ───────────────────────────────────────────────────────────────

    public function index()
    {
        $reservas = Reserva::with(['user', 'habitacion', 'pagos'])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.reservas.index', compact('reservas'));
    }

    // ── Formulario crear ──────────────────────────────────────────────────────

    public function create()
    {
        $clientes    = User::where('rol', 'cliente')->where('estado', 'activo')->orderBy('name')->get();
        $habitaciones = Habitacion::where('estado', 'disponible')->orderBy('numero')->get();

        return view('admin.reservas.create', compact('clientes', 'habitaciones'));
    }

    // ── Guardar nueva reserva ─────────────────────────────────────────────────

    public function store(RegistrarReservaRequest $request)
    {
        $data        = $request->validated();
        $habitacion  = Habitacion::findOrFail($data['habitacion_id']);

        // Verificar disponibilidad
        if ($this->estaOcupada($habitacion->id, $data['fecha_ingreso'], $data['fecha_salida'])) {
            return back()->withErrors(['habitacion_id' => 'La habitación no está disponible en esas fechas.'])->withInput();
        }

        // Verificar capacidad
        if ($data['numero_personas'] > $habitacion->capacidad) {
            return back()->withErrors(['numero_personas' => "La habitación tiene capacidad máxima de {$habitacion->capacidad} personas."])->withInput();
        }

        // Calcular total
        $entrada  = Carbon::parse($data['fecha_ingreso']);
        $salida   = Carbon::parse($data['fecha_salida']);
        $noches   = $entrada->diffInDays($salida);
        $pNoche   = $habitacion->precioParaPersonas((int) $data['numero_personas']);
        $total    = round($pNoche * $noches, 2);
        $anticipo = $total; // Admin registra pago completo

        try {
            DB::transaction(function () use ($data, $total, $anticipo, &$reserva) {
                $reserva = Reserva::create([
                    'user_id'         => $data['user_id'],
                    'habitacion_id'   => $data['habitacion_id'],
                    'fecha_ingreso'   => $data['fecha_ingreso'],
                    'fecha_salida'    => $data['fecha_salida'],
                    'numero_personas' => $data['numero_personas'],
                    'total'           => $total,
                    'pago_anticipado' => $anticipo,
                    'estado'          => $data['estado'],
                ]);

                Pago::create([
                    'reserva_id'  => $reserva->id,
                    'total'       => $total,
                    'metodo_pago' => $data['metodo_pago'],
                ]);
            });

            return redirect()->route('admin.reservas')
                ->with('success', 'Reserva y pago registrados exitosamente.');

        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Error al guardar la reserva: ' . $e->getMessage()])->withInput();
        }
    }

    // ── Formulario editar ─────────────────────────────────────────────────────

    public function edit(int $id)
    {
        $reserva      = Reserva::with('habitacion')->findOrFail($id);
        $clientes     = User::where('rol', 'cliente')->where('estado', 'activo')->orderBy('name')->get();
        $habitaciones = Habitacion::where('estado', 'disponible')->orderBy('numero')->get();

        return view('admin.reservas.edit', compact('reserva', 'clientes', 'habitaciones'));
    }

    // ── Actualizar ────────────────────────────────────────────────────────────

    public function update(EditarReservaRequest $request, int $id)
    {
        $reserva = Reserva::findOrFail($id);
        $data    = $request->validated();
        $habitacion = Habitacion::findOrFail($data['habitacion_id']);

        // Verificar capacidad
        if ($data['numero_personas'] > $habitacion->capacidad) {
            return back()->withErrors(['numero_personas' => "La habitación tiene capacidad máxima de {$habitacion->capacidad} personas."])->withInput();
        }

        // Verificar disponibilidad (excluyendo la reserva actual)
        if ($this->estaOcupada($habitacion->id, $data['fecha_ingreso'], $data['fecha_salida'], $id)) {
            return back()->withErrors(['habitacion_id' => 'La habitación no está disponible en esas fechas.'])->withInput();
        }

        $reserva->update([
            'user_id'         => $data['user_id'],
            'habitacion_id'   => $data['habitacion_id'],
            'fecha_ingreso'   => $data['fecha_ingreso'],
            'fecha_salida'    => $data['fecha_salida'],
            'numero_personas' => $data['numero_personas'],
            'estado'          => $data['estado'],
        ]);

        return redirect()->route('admin.reservas')
            ->with('success', 'Reserva actualizada correctamente.');
    }

    // ── Cancelar reserva ──────────────────────────────────────────────────────

    public function cancelar(int $id)
    {
        $reserva = Reserva::findOrFail($id);
        $reserva->update(['estado' => 'cancelada']);

        return redirect()->route('admin.reservas')
            ->with('success', 'Reserva cancelada.');
    }

    // ── Confirmar reserva (desde pendiente) ───────────────────────────────────

    public function confirmar(int $id)
    {
        $reserva = Reserva::where('id', $id)->where('estado', 'pendiente')->firstOrFail();
        $reserva->update(['estado' => 'confirmada']);

        return redirect()->route('admin.reservas')
            ->with('success', 'Reserva confirmada correctamente.');
    }

    // ── Helper: verificar solapamiento ────────────────────────────────────────

    private function estaOcupada(int $habId, string $entrada, string $salida, ?int $excluirId = null): bool
    {
        $q = Reserva::where('habitacion_id', $habId)
            ->whereIn('estado', ['pendiente', 'confirmada'])
            ->where(function ($q) use ($entrada, $salida) {
                $q->whereBetween('fecha_ingreso', [$entrada, $salida])
                  ->orWhereBetween('fecha_salida',  [$entrada, $salida])
                  ->orWhere(function ($q2) use ($entrada, $salida) {
                      $q2->where('fecha_ingreso', '<=', $entrada)
                         ->where('fecha_salida',  '>=', $salida);
                  });
            });

        if ($excluirId) {
            $q->where('id', '!=', $excluirId);
        }

        return $q->exists();
    }
}
