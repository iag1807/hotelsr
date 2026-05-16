<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'documento',
        'tipo_documento',
        'name',
        'genero',
        'celular',
        'rol',
        'estado',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relaciones ────────────────────────────────────────────────────────────

    public function reservas()
    {
        return $this->hasMany(Reserva::class);
    }

    public function checksIn()
    {
        return $this->hasMany(CheckIn::class, 'registrado_por');
    }

    public function checksOut()
    {
        return $this->hasMany(CheckOut::class, 'registrado_por');
    }

    public function facturas()
    {
        return $this->hasMany(Facturacion::class);
    }

    // ── Helpers ───────────────────────────────────────────────────────────────

    public function esCliente(): bool
    {
        return $this->rol === 'cliente';
    }

    public function esAdmin(): bool
    {
        return $this->rol === 'admin';
    }

    public function estaActivo(): bool
    {
        return $this->estado === 'activo';
    }

    /**
     * Saludo personalizado según género.
     */
    public function saludo(): string
    {
        return $this->genero === 'femenino' ? 'Bienvenida' : 'Bienvenido';
    }
}
