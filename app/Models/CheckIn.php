<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckIn extends Model
{
    protected $table = 'checks_in';

    protected $fillable = [
        'reserva_id',
        'registrado_por',
        'fecha_hora_check_in',
        'numero_personas',
        'observaciones',
    ];

    protected $casts = [
        'fecha_hora_check_in' => 'datetime',
        'numero_personas'     => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}
