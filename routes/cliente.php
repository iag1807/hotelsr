<?php

use App\Http\Controllers\Cliente\DashboardController;
use App\Http\Controllers\Cliente\FacturaController;
use App\Http\Controllers\Cliente\PerfilController;
use App\Http\Controllers\Cliente\ReservaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Rutas — Panel Cliente
|--------------------------------------------------------------------------
|
| Registrar el middleware en bootstrap/app.php:
|
|   ->withMiddleware(function (Middleware $middleware) {
|       $middleware->alias([
|           'es_cliente' => \App\Http\Middleware\EsCliente::class,
|       ]);
|   })
|
| Incluir este archivo en routes/web.php:
|
|   require __DIR__ . '/cliente.php';
|
*/

Route::prefix('cliente')
    ->name('cliente.')
    ->middleware(['auth', 'es_cliente'])
    ->group(function () {

        // Dashboard — buscador de habitaciones
        Route::get('/',  [DashboardController::class, 'index'])->name('dashboard');

        // Mis reservas
        Route::get('/reservas',  [ReservaController::class, 'index'])->name('reservas');
        Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');

        // Mis facturas
        Route::get('/facturas',              [FacturaController::class, 'index'])->name('facturas');
        Route::get('/facturas/{id}/pdf',     [FacturaController::class, 'descargar'])->name('facturas.pdf');

        // Mi perfil
        Route::get('/perfil',                    [PerfilController::class, 'index'])->name('perfil');
        Route::put('/perfil',                    [PerfilController::class, 'actualizar'])->name('perfil.actualizar');
        Route::put('/perfil/contrasena',         [PerfilController::class, 'cambiarContrasena'])->name('perfil.contrasena');
    });
