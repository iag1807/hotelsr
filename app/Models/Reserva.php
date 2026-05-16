<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $fillable = [
        'habitacion_id',
        'user_id',
        'fecha_ingreso',
        'fecha_salida',
        'numero_personas',
        'total',
        'pago_anticipado',
        'estado',
        'comprobante_pago',
    ];

    protected $casts = [
        'fecha_ingreso'   => 'date',
        'fecha_salida'    => 'date',
        'total'           => 'decimal:2',
        'pago_anticipado' => 'decimal:2',
        'numero_personas' => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function habitacion()
    {
        return $this->belongsTo(Habitacion::class);
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class);
    }

    public function checkIn()
    {
        return $this->hasOne(CheckIn::class);
    }

    public function checkOut()
    {
        return $this->hasOne(CheckOut::class);
    }

    public function facturacion()
    {
        return $this->hasMany(Facturacion::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Reservas con fecha de ingreso hoy o futura (excluyendo canceladas).
     */
    public function scopeProximas($query)
    {
        return $query
            ->where('fecha_ingreso', '>=', now()->toDateString())
            ->whereNotIn('estado', ['cancelada']);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Número de noches de la reserva.
     */
    public function noches(): int
    {
        return (int) Carbon::parse($this->fecha_ingreso)
                           ->diffInDays(Carbon::parse($this->fecha_salida));
    }

    /**
     * Saldo pendiente de pago (total - anticipo).
     */
    public function saldoPendiente(): float
    {
        return round((float) $this->total - (float) $this->pago_anticipado, 2);
    }

    /**
     * Clase CSS del badge según estado de la reserva.
     */
    public function badgeClass(): string
    {
        return match ($this->estado) {
            'confirmada' => 'badge-confirmada',
            'finalizada' => 'badge-finalizada',
            'cancelada'  => 'badge-cancelada',
            default      => 'badge-pendiente',
        };
    }

    /**
     * Primera factura asociada (atajo útil para vistas).
     */
    public function facturaActual(): ?Facturacion
    {
        return $this->facturacion->first();
    }
}
