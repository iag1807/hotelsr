<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use App\Models\Facturacion;
use App\Services\FacturacionService;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
    public function __construct(private FacturacionService $facturacionService) {}

    // ── Vista: listado de facturas ────────────────────────────────────────────

    public function index()
    {
        $user = auth()->user();

        // Facturacion es hasMany desde Reserva, pero también tiene user_id propio
        $facturas = Facturacion::with(['reserva.habitacion'])
            ->where('user_id', $user->id)
            ->orderByDesc('fecha_factura')
            ->orderByDesc('id')
            ->get();

        return view('cliente.facturas', compact('user', 'facturas'));
    }

    // ── Descargar / visualizar PDF de una factura ─────────────────────────────

    public function descargar(int $id, Request $request)
    {
        $user = auth()->user();

        // Solo puede ver sus propias facturas
        $factura = Facturacion::where('id', $id)
            ->where('user_id', $user->id)
            ->with('reserva')
            ->firstOrFail();

        $rutaPdf = storage_path('app/public/facturas/' . $factura->numero_factura . '.pdf');

        // Regenerar si el PDF fue eliminado del disco
        if (!file_exists($rutaPdf)) {
            $idGenerado = $this->facturacionService->generarFactura($factura->reserva);
            if (!$idGenerado) {
                abort(500, 'No se pudo regenerar la factura.');
            }
        }

        if (!file_exists($rutaPdf)) {
            abort(404, 'PDF no encontrado.');
        }

        $disposicion = $request->has('ver') ? 'inline' : 'attachment';

        return response()->file($rutaPdf, [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => $disposicion . '; filename="' . basename($rutaPdf) . '"',
        ]);
    }
}
