<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Habitacion extends Model
{
    protected $table = 'habitaciones';

    protected $fillable = [
        'numero',
        'tipo_habitacion',
        'capacidad',
        'precio',
        'descripcion',
        'estado',
        'precio_persona_adicional',
    ];

    protected $casts = [
        'precio'                   => 'decimal:2',
        'precio_persona_adicional' => 'decimal:2',
        'capacidad'                => 'integer',
    ];

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    // ── Scopes ────────────────────────────────────────────────────────────────

    /**
     * Solo habitaciones disponibles (no en mantenimiento).
     */
    public function scopeDisponible($query)
    {
        return $query->where('estado', 'disponible');
    }

    /**
     * Habitaciones libres para un rango de fechas y número mínimo de personas.
     * Estado 'disponible' + capacidad suficiente + sin reservas activas solapadas.
     */
    public function scopeDisponibleParaFechas($query, string $entrada, string $salida, int $personas)
    {
        return $query
            ->where('estado', 'disponible')
            ->where('capacidad', '>=', $personas)
            ->whereNotIn('id', function ($sub) use ($entrada, $salida) {
                $sub->select('habitacion_id')
                    ->from('reservas')
                    ->whereNotIn('estado', ['cancelada'])
                    ->where(function ($q) use ($entrada, $salida) {
                        // Solapamiento: alguna fecha del rango cae dentro de una reserva existente
                        $q->whereBetween('fecha_ingreso', [$entrada, $salida])
                          ->orWhereBetween('fecha_salida', [$entrada, $salida])
                          ->orWhere(function ($q2) use ($entrada, $salida) {
                              $q2->where('fecha_ingreso', '<=', $entrada)
                                 ->where('fecha_salida',  '>=', $salida);
                          });
                    });
            });
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    /**
     * Precio por noche según número de personas.
     * 1 persona  → precio base
     * 2+ personas → precio base + (personas - 1) × precio_persona_adicional
     */
    public function precioParaPersonas(int $personas): float
    {
        $extra = max(0, $personas - 1);
        return (float) $this->precio + ($extra * (float) $this->precio_persona_adicional);
    }

    /**
     * ¿Está disponible para ocupar?
     */
    public function estaDisponible(): bool
    {
        return $this->estado === 'disponible';
    }
}
