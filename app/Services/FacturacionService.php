<?php

namespace App\Services;

use App\Models\Facturacion;
use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FacturacionService
{
    /**
     * Genera la factura PDF y la registra en `facturacion`.
     * Si ya existe una factura para la reserva, devuelve su id sin duplicar.
     *
     * @return int|null  id de Facturacion creada o existente, null si hay error
     */
    public function generarFactura(Reserva $reserva): ?int
    {
        $reserva->loadMissing(['user', 'habitacion']);

        // Evitar duplicados (Reserva hasMany Facturacion)
        $existente = Facturacion::where('reserva_id', $reserva->id)->first();
        if ($existente) {
            return $existente->id;
        }

        $user       = $reserva->user;
        $habitacion = $reserva->habitacion;

        // ── Calcular valores ──────────────────────────────────────────────────
        $entrada   = Carbon::parse($reserva->fecha_ingreso);
        $salida    = Carbon::parse($reserva->fecha_salida);
        $noches    = (int) $entrada->diffInDays($salida);
        $subtotal  = (float) $reserva->total;
        $impuestos = 0.00;
        $total     = $subtotal + $impuestos;
        $anticipo  = (float) $reserva->pago_anticipado;
        $saldo     = round($total - $anticipo, 2);

        $numeroFactura = 'FAC-' . now()->format('Ymd') . '-' . str_pad($reserva->id, 5, '0', STR_PAD_LEFT);

        // ── Directorio storage/app/public/facturas/ ───────────────────────────
        $dirFacturas = storage_path('app/public/facturas/');
        if (!is_dir($dirFacturas)) {
            mkdir($dirFacturas, 0755, true);
        }

        $nombrePdf = $numeroFactura . '.pdf';
        $rutaAbs   = $dirFacturas . $nombrePdf;

        // ── Generar PDF ───────────────────────────────────────────────────────
        try {
            $this->construirPdf($rutaAbs, $numeroFactura, $user, $habitacion, $reserva, [
                'entrada'   => $entrada,
                'salida'    => $salida,
                'noches'    => $noches,
                'subtotal'  => $subtotal,
                'impuestos' => $impuestos,
                'total'     => $total,
                'anticipo'  => $anticipo,
                'saldo'     => $saldo,
            ]);
        } catch (\Throwable $e) {
            Log::error('FacturacionService PDF error: ' . $e->getMessage());
            return null;
        }

        // ── Registrar en BD ───────────────────────────────────────────────────
        $factura = Facturacion::create([
            'reserva_id'     => $reserva->id,
            'user_id'        => $user->id,
            'numero_factura' => $numeroFactura,
            'subtotal'       => $subtotal,
            'impuestos'      => $impuestos,
            'total'          => $total,
            'estado'         => 'pendiente',
            'metodo_pago'    => 'transferencia',
            'fecha_factura'  => now()->toDateString(),
            'observaciones'  => 'Generada automáticamente. Archivo: ' . $nombrePdf,
        ]);

        return $factura->id;
    }

    // ── Construcción interna del PDF con FPDF ─────────────────────────────────

    private function construirPdf(
        string $rutaAbs,
        string $numeroFactura,
        $user,
        $habitacion,
        Reserva $reserva,
        array $v
    ): void {
        $this->cargarFpdf();

        $pdf = new \FPDF('P', 'mm', 'A4');
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->AddPage();

        // ── Paleta ────────────────────────────────────────────────────────────
        $amarillo  = [245, 240, 200];
        $marron    = [61,  32,  0];
        $dorado    = [180, 140, 40];
        $grisTexto = [80,  70,  60];
        $grisClaro = [200, 190, 175];
        $fondoFila = [252, 250, 244];
        $blanco    = [255, 255, 255];
        $izq       = 15;

        // ── Cabecera ──────────────────────────────────────────────────────────
        $pdf->SetFillColor(...$amarillo);
        $pdf->Rect(0, 0, 210, 38, 'F');

        $logo = public_path('images/logo.png');
        if (file_exists($logo)) {
            $pdf->Image($logo, 168, 1, 34, 34);
        }

        $pdf->SetXY($izq, 5);
        $pdf->SetFont('Helvetica', 'B', 24);
        $pdf->SetTextColor(...$marron);
        $pdf->Cell(145, 10, 'FACTURA', 0, 1, 'L');

        $pdf->SetXY($izq, 18);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetDrawColor(...$dorado);
        $pdf->SetFillColor(...$amarillo);
        $pdf->SetLineWidth(0.5);
        $pdf->Cell(62, 6, 'N' . chr(176) . ': ' . $numeroFactura, 1, 0, 'L', true);

        $pdf->SetDrawColor(...$marron);
        $pdf->SetLineWidth(0.4);
        $pdf->Line(0, 38, 210, 38);
        $pdf->SetY(42);

        // ── Datos cliente / empresa ───────────────────────────────────────────
        $yDatos = $pdf->GetY();

        $pdf->SetXY($izq, $yDatos);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetTextColor(...$marron);
        $pdf->Cell(85, 5, 'DATOS DEL CLIENTE', 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetTextColor(...$grisTexto);
        foreach ([
            'Nombre: '    . $user->name,
            'Correo: '    . $user->email,
            'Documento: ' . $user->documento,
            'Celular: '   . ($user->celular ?? '-'),
        ] as $linea) {
            $pdf->SetX($izq);
            $pdf->Cell(85, 4, $linea, 0, 1);
        }

        $pdf->SetXY(110, $yDatos);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetTextColor(...$marron);
        $pdf->Cell(85, 5, 'DATOS DE LA EMPRESA', 0, 1, 'L');

        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetTextColor(...$grisTexto);
        $yEmp = $yDatos + 5;
        foreach ([
            'Hotel Sue' . chr(241) . 'o Real',
            'Marinilla, Antioquia',
            '3226483067',
            'Autopista Medell' . chr(237) . 'n-Bogot' . chr(225) . ' #45-132',
        ] as $linea) {
            $pdf->SetXY(110, $yEmp);
            $pdf->Cell(85, 4, $linea, 0, 1);
            $yEmp += 4;
        }

        $yFin = max($pdf->GetY(), $yEmp) + 2;
        $pdf->SetDrawColor(...$grisClaro);
        $pdf->SetLineWidth(0.2);
        $pdf->Line(106, $yDatos, 106, $yFin);
        $pdf->SetY($yFin + 3);
        $pdf->Line($izq, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(4);

        // ── Tabla detalles ────────────────────────────────────────────────────
        $pdf->SetX($izq);
        $pdf->SetFillColor(...$marron);
        $pdf->SetTextColor(...$blanco);
        $pdf->SetFont('Helvetica', 'B', 7);
        $pdf->Cell(55, 6, 'Habitaci' . chr(243) . 'n', 1, 0, 'C', true);
        $pdf->Cell(35, 6, 'Fecha de entrada',          1, 0, 'C', true);
        $pdf->Cell(35, 6, 'Fecha de salida',            1, 0, 'C', true);
        $pdf->Cell(30, 6, 'Noches',                     1, 0, 'C', true);
        $pdf->Cell(25, 6, 'Total',                      1, 1, 'C', true);

        $pdf->SetFillColor(...$fondoFila);
        $pdf->SetTextColor(...$grisTexto);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetX($izq);
        $tipoHab = 'Habitaci' . chr(243) . 'n ' . ucfirst($habitacion->tipo_habitacion) . ' #' . $habitacion->numero;
        $pdf->Cell(55, 7, $tipoHab,                                                              1, 0, 'L', true);
        $pdf->Cell(35, 7, $v['entrada']->format('d/m/Y'),                                        1, 0, 'C', true);
        $pdf->Cell(35, 7, $v['salida']->format('d/m/Y'),                                         1, 0, 'C', true);
        $pdf->Cell(30, 7, $v['noches'] . ' noche' . ($v['noches'] > 1 ? 's' : ''),              1, 0, 'C', true);
        $pdf->Cell(25, 7, '$' . number_format($v['subtotal'], 0, ',', '.'),                       1, 1, 'R', true);

        $pdf->Ln(2);
        $ySub = $pdf->GetY();

        $pdf->SetXY($izq, $ySub);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetTextColor(...$grisTexto);
        $pdf->Cell(90, 5, 'N' . chr(250) . 'mero de personas: ' . $reserva->numero_personas, 0, 0);

        $pdf->SetXY(115, $ySub);
        $pdf->Cell(55, 5, 'Subtotal', 0, 0, 'R');
        $pdf->Cell(25, 5, '$' . number_format($v['subtotal'], 0, ',', '.'), 0, 1, 'R');
        $pdf->SetX(115);
        $pdf->Cell(55, 5, 'Impuestos (IVA incluido)', 0, 0, 'R');
        $pdf->Cell(25, 5, '$ 0,00', 0, 1, 'R');

        $pdf->Ln(2);
        $pdf->SetX($izq);
        $pdf->SetFillColor(...$marron);
        $pdf->SetTextColor(...$blanco);
        $pdf->SetFont('Helvetica', 'B', 9);
        $pdf->Cell(155, 8, 'TOTAL', 1, 0, 'L', true);
        $pdf->Cell(25,  8, '$' . number_format($v['total'], 0, ',', '.'), 1, 1, 'R', true);

        $pdf->Ln(2);
        $pdf->SetX(115);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetTextColor(...$grisTexto);
        $pdf->Cell(55, 5, 'Anticipo pagado (50%)', 0, 0, 'R');
        $pdf->Cell(25, 5, '$' . number_format($v['anticipo'], 0, ',', '.'), 0, 1, 'R');
        $pdf->SetX(115);
        $pdf->Cell(55, 5, 'Saldo pendiente', 0, 0, 'R');
        $pdf->Cell(25, 5, '$' . number_format($v['saldo'], 0, ',', '.'), 0, 1, 'R');

        // ── Info de pago ──────────────────────────────────────────────────────
        $pdf->Ln(5);
        $pdf->SetDrawColor(...$grisClaro);
        $pdf->Line($izq, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(4);
        $pdf->SetX($izq);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->SetTextColor(...$marron);
        $pdf->Cell(0, 5, 'INFORMACI' . chr(211) . 'N DE PAGO', 0, 1);
        $pdf->SetFont('Helvetica', '', 7);
        $pdf->SetTextColor(...$grisTexto);
        foreach ([
            'Transferencia bancaria',
            'Bancolombia — Martha Cecilia Carvajal Bran',
            'Cuenta de Ahorros: 64759538917',
            'Pago anticipado (50%) — Estado: Pendiente de confirmar',
        ] as $l) {
            $pdf->SetX($izq);
            $pdf->Cell(0, 4, $l, 0, 1);
        }

        // ── Nota legal ────────────────────────────────────────────────────────
        $pdf->Ln(4);
        $pdf->SetDrawColor(...$grisClaro);
        $pdf->Line($izq, $pdf->GetY(), 195, $pdf->GetY());
        $pdf->Ln(4);
        $pdf->SetX($izq);
        $pdf->SetFont('Helvetica', 'I', 6);
        $pdf->SetTextColor(...$grisClaro);
        $pdf->MultiCell(180, 3.5,
            'Esta factura corresponde a una reserva en estado PENDIENTE de confirmaci' . chr(243) . 'n. ' .
            'El anticipo ha sido recibido v' . chr(237) . 'a transferencia Bancolombia y est' . chr(225) .
            ' sujeto a verificaci' . chr(243) . 'n. El saldo pendiente debe cancelarse al momento del check-in.',
            0, 'L');

        // ── Pie de página ─────────────────────────────────────────────────────
        $pdf->SetXY($izq, 282);
        $pdf->SetDrawColor(...$marron);
        $pdf->SetLineWidth(0.3);
        $pdf->Line($izq, 282, 195, 282);
        $pdf->SetXY($izq, 284);
        $pdf->SetFont('Helvetica', '', 6);
        $pdf->SetTextColor(...$marron);
        $pdf->Cell(0, 4,
            'HOTEL SUE' . chr(209) . 'O REAL  -  GENERADO EL ' . now()->format('d/m/Y') .
            '  -  FACTURA N' . chr(176) . ' ' . $numeroFactura,
            0, 0, 'C');

        $pdf->Output('F', $rutaAbs);
    }

    private function cargarFpdf(): void
    {
        if (class_exists('FPDF')) return;

        $rutas = [
            base_path('vendor/setasign/fpdf/fpdf.php'),  // composer require setasign/fpdf
            base_path('lib/fpdf/fpdf.php'),               // manual
        ];

        foreach ($rutas as $ruta) {
            if (file_exists($ruta)) {
                require_once $ruta;
                return;
            }
        }

        throw new \RuntimeException(
            'FPDF no encontrado. Instálalo: composer require setasign/fpdf ' .
            'o copia fpdf.php en lib/fpdf/fpdf.php'
        );
    }
}
