<?php

use App\Http\Controllers\Admin\CheckInOutController;
use App\Http\Controllers\Admin\ClienteController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HabitacionController;
use App\Http\Controllers\Admin\ReservaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas — Panel Administrador
|--------------------------------------------------------------------------
|
| Agregar el middleware en bootstrap/app.php:
|
|   $middleware->alias([
|       'es_admin'    => \App\Http\Middleware\EsAdmin::class,
|       'es_cliente'  => \App\Http\Middleware\EsCliente::class,
|   ]);
|
| Incluir en routes/web.php:
|
|   require __DIR__ . '/admin.php';
|
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'es_admin'])
    ->group(function () {

        // Dashboard
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // ── Checks In/Out ─────────────────────────────────────────────────────
        Route::get('/ingresos',                    [CheckInOutController::class, 'index'])->name('checks');
        Route::post('/ingresos/{id}/checkin',      [CheckInOutController::class, 'checkIn'])->name('checks.checkin');
        Route::post('/ingresos/{id}/checkout',     [CheckInOutController::class, 'checkOut'])->name('checks.checkout');

        // ── Reservas ──────────────────────────────────────────────────────────
        Route::get('/reservas',                    [ReservaController::class, 'index'])->name('reservas');
        Route::get('/reservas/crear',              [ReservaController::class, 'create'])->name('reservas.create');
        Route::post('/reservas',                   [ReservaController::class, 'store'])->name('reservas.store');
        Route::get('/reservas/{id}/editar',        [ReservaController::class, 'edit'])->name('reservas.edit');
        Route::put('/reservas/{id}',               [ReservaController::class, 'update'])->name('reservas.update');
        Route::patch('/reservas/{id}/cancelar',    [ReservaController::class, 'cancelar'])->name('reservas.cancelar');
        Route::patch('/reservas/{id}/confirmar',   [ReservaController::class, 'confirmar'])->name('reservas.confirmar');

        // ── Habitaciones ──────────────────────────────────────────────────────
        Route::get('/habitaciones',                [HabitacionController::class, 'index'])->name('habitaciones');
        Route::get('/habitaciones/crear',          [HabitacionController::class, 'create'])->name('habitaciones.create');
        Route::post('/habitaciones',               [HabitacionController::class, 'store'])->name('habitaciones.store');
        Route::get('/habitaciones/{id}/editar',    [HabitacionController::class, 'edit'])->name('habitaciones.edit');
        Route::put('/habitaciones/{id}',           [HabitacionController::class, 'update'])->name('habitaciones.update');
        Route::patch('/habitaciones/{id}/estado',  [HabitacionController::class, 'toggleEstado'])->name('habitaciones.estado');

        // ── Clientes / Huéspedes ──────────────────────────────────────────────
        Route::get('/clientes',                    [ClienteController::class, 'index'])->name('clientes');
        Route::get('/clientes/crear',              [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes',                   [ClienteController::class, 'store'])->name('clientes.store');
        Route::get('/clientes/{id}/editar',        [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('/clientes/{id}',               [ClienteController::class, 'update'])->name('clientes.update');
        Route::patch('/clientes/{id}/estado',      [ClienteController::class, 'toggleEstado'])->name('clientes.estado');
    });
