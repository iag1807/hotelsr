<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cliente\ProcesarReservaRequest;
use App\Models\Habitacion;
use App\Models\Pago;
use App\Models\Reserva;
use App\Services\FacturacionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReservaController extends Controller
{
    public function __construct(private FacturacionService $facturacionService) {}

    // ── Vista: mis reservas ───────────────────────────────────────────────────

    public function index()
    {
        $user = auth()->user();

        // Próxima reserva activa
        $proximaReserva = Reserva::with('habitacion')
            ->where('user_id', $user->id)
            ->where('fecha_ingreso', '>=', now()->toDateString())
            ->whereNotIn('estado', ['cancelada'])
            ->orderBy('fecha_ingreso')
            ->first();

        // Historial completo con relaciones necesarias para la tabla
        $historial = Reserva::with(['habitacion', 'pagos'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        return view('cliente.reservas', compact('user', 'proximaReserva', 'historial'));
    }

    // ── POST JSON: crear reserva desde el modal ────────────────────────────────

    public function store(ProcesarReservaRequest $request)
    {
        $data = $request->validated();
        $user = auth()->user();

        // Verificar que la habitación existe y está disponible
        $habitacion = Habitacion::where('id', $data['id_habitacion'])
            ->where('estado', 'disponible')   // ← 'disponible', no 'activa'
            ->first();

        if (!$habitacion) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'La habitación no está disponible.',
            ], 422);
        }

        // ── Calcular precios ──────────────────────────────────────────────────
        $entrada  = Carbon::parse($data['fecha_ingreso']);
        $salida   = Carbon::parse($data['fecha_salida']);
        $noches   = $entrada->diffInDays($salida);
        $pNoche   = $habitacion->precioParaPersonas((int) $data['num_personas']);
        $total    = round($pNoche * $noches, 2);
        $anticipo = round($total * 0.5, 2);

        // ── Doble verificación de disponibilidad (race condition) ─────────────
        $ocupada = Reserva::where('habitacion_id', $habitacion->id)
            ->whereNotIn('estado', ['cancelada'])
            ->where(function ($q) use ($data) {
                $q->whereBetween('fecha_ingreso', [$data['fecha_ingreso'], $data['fecha_salida']])
                  ->orWhereBetween('fecha_salida',  [$data['fecha_ingreso'], $data['fecha_salida']])
                  ->orWhere(function ($q2) use ($data) {
                      $q2->where('fecha_ingreso', '<=', $data['fecha_ingreso'])
                         ->where('fecha_salida',  '>=', $data['fecha_salida']);
                  });
            })->exists();

        if ($ocupada) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'La habitación ya no está disponible para esas fechas. Por favor elige otra.',
            ], 409);
        }

        // ── Guardar comprobante ───────────────────────────────────────────────
        $rutaComprobante = $this->guardarComprobante($data['comprobante'], $user->documento);

        if (!$rutaComprobante) {
            return response()->json([
                'exito'   => false,
                'mensaje' => 'No se pudo guardar el comprobante. Intenta de nuevo.',
            ], 500);
        }

        // ── Transacción: reserva + pago + factura ─────────────────────────────
        $idReserva = null;
        $idFactura = null;

        try {
            DB::transaction(function () use (
                $user, $habitacion, $data, $total, $anticipo,
                $rutaComprobante, &$idReserva, &$idFactura
            ) {
                $reserva = Reserva::create([
                    'user_id'          => $user->id,
                    'habitacion_id'    => $habitacion->id,
                    'fecha_ingreso'    => $data['fecha_ingreso'],
                    'fecha_salida'     => $data['fecha_salida'],
                    'numero_personas'  => (int) $data['num_personas'],
                    'total'            => $total,
                    'pago_anticipado'  => $anticipo,
                    'estado'           => 'pendiente',
                    'comprobante_pago' => $rutaComprobante,
                ]);

                Pago::create([
                    'reserva_id'  => $reserva->id,
                    'total'       => $anticipo,
                    'metodo_pago' => 'transferencia',
                ]);

                $idReserva = $reserva->id;

                // Generar factura PDF y registrar en BD
                $idFactura = $this->facturacionService->generarFactura($reserva);
            });

            return response()->json([
                'exito'      => true,
                'id_reserva' => $idReserva,
                'id_factura' => $idFactura,
                'mensaje'    => 'Reserva registrada correctamente. Tu factura está disponible en Mis Facturas.',
            ]);

        } catch (\Throwable $e) {
            // Revertir el comprobante si la transacción falla
            Storage::disk('public')->delete($rutaComprobante);

            return response()->json([
                'exito'   => false,
                'mensaje' => 'Error al guardar la reserva: ' . $e->getMessage(),
            ], 500);
        }
    }

    // ── Helpers privados ──────────────────────────────────────────────────────

    private function guardarComprobante(string $base64, string $documento): ?string
    {
        try {
            $ext = 'jpg';
            if (str_contains($base64, 'image/png'))           $ext = 'png';
            elseif (str_contains($base64, 'application/pdf')) $ext = 'pdf';

            $datos = $base64;
            if (str_contains($base64, ',')) {
                $datos = explode(',', $base64, 2)[1];
            }

            $nombre = 'comp_' . $documento . '_' . time() . '.' . $ext;
            $ruta   = 'comprobantes/' . $nombre;

            Storage::disk('public')->put($ruta, base64_decode($datos));

            return $ruta;
        } catch (\Throwable) {
            return null;
        }
    }
}
