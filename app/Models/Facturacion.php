<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Facturacion extends Model
{
    protected $table = 'facturacion';

    protected $fillable = [
        'reserva_id',
        'user_id',
        'numero_factura',
        'subtotal',
        'impuestos',
        'total',
        'estado',
        'metodo_pago',
        'fecha_factura',
        'observaciones',
    ];

    protected $casts = [
        'subtotal'      => 'decimal:2',
        'impuestos'     => 'decimal:2',
        'total'         => 'decimal:2',
        'fecha_factura' => 'date',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Clase CSS del badge según estado de la factura.
     */
    public function badgeClass(): string
    {
        return match ($this->estado) {
            'pagada'   => 'badge-confirmada',
            'cancelada'=> 'badge-cancelada',
            default    => 'badge-pendiente',
        };
    }
}
