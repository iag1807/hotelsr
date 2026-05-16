<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos';

    protected $fillable = [
        'reserva_id',
        'total',
        'metodo_pago',
    ];

    protected $casts = [
        'total' => 'decimal:2',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }
}
