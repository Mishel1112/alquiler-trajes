<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TrajeController;
use App\Http\Controllers\AlquilerController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsuarioController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// --- MÓDULO DE STOCK Y ALQUILERES ---
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/stock', function () {
        return view('auth.stock');
    })->name('stock');

    Route::get('/stock/{categoria}', [TrajeController::class, 'porCategoria'])->name('stock.categoria');
    Route::get('/traje/detalle/{id}', [TrajeController::class, 'show'])->name('trajes.show');

    Route::get('/alquileres/seleccionar', function () {
        return redirect()->route('stock', ['modo' => 'alquilar']);
    })->name('alquiler.seleccionar');

    // --- RUTAS DE ALQUILER INMEDIATO ---
    Route::get('/registrar/{id?}', [AlquilerController::class, 'create'])->name('registrar');
    Route::post('/registrar', [AlquilerController::class, 'store'])->name('alquileres.store');
    Route::post('/registrar-alquiler', [AlquilerController::class, 'store'])->name('alquiler.store');

    // --- RUTAS EXCLUSIVAS PARA RESERVAS ---
    Route::get('/reservas/crear', [AlquilerController::class, 'createReserva'])->name('reservas.create');
    Route::post('/reservas/guardar', [AlquilerController::class, 'storeReserva'])->name('reservas.store');
    Route::post('/reservas/vista-previa', [AlquilerController::class, 'vistaPreviaReserva'])->name('reservas.vista_previa');

    Route::post('/alquileres/vista-previa', [AlquilerController::class, 'vistaPrevia'])->name('alquileres.vista_previa');

    Route::get('/alquileres/confirmar/{id}', [AlquilerController::class, 'mostrarConfirmacion'])->name('alquileres.confirmar');
    Route::post('/alquileres/procesar-confirmacion', [AlquilerController::class, 'procesarConfirmacion'])->name('alquileres.procesar');

    // --- GESTIÓN DE TRAJES (SOLO ADMINISTRADOR) ---
    Route::middleware(['admin'])->group(function () {
        Route::get('/trajes/crear/{categoria}', [TrajeController::class, 'create'])->name('trajes.create');
        Route::post('/trajes', [TrajeController::class, 'store'])->name('trajes.store');
        Route::put('/trajes/{id}', [TrajeController::class, 'update'])->name('trajes.update');
        Route::delete('/trajes/{id}', [TrajeController::class, 'destroy'])->name('trajes.destroy');

        // --- RUTAS DEL MÓDULO DE REPORTES (SOLO ADMINISTRADOR) ---
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes');
        Route::get('/reportes/diario', [ReporteController::class, 'diario'])->name('reportes.diario');
        Route::get('/reportes/mensual', [ReporteController::class, 'mensual'])->name('reportes.mensual');
        Route::get('/reportes/anual', [ReporteController::class, 'anual'])->name('reportes.anual');

        // --- GESTIÓN DE USUARIOS (SOLO ADMINISTRADOR) ---
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::delete('/usuarios/{id}', [UsuarioController::class, 'destroy'])->name('usuarios.destroy');
    });

    // --- RUTA PARA EL RECIBO DE ALQUILER / RESERVA CON ID ---
    Route::get('/alquiler/recibo/{id}', [AlquilerController::class, 'verRecibo'])->name('alquiler.recibo');

    // --- ALIAS PARA IMPRIMIR RECIBO ---
    Route::get('/recibo/imprimir', [AlquilerController::class, 'imprimirRecibo'])->name('recibo.imprimir');

    // --- RUTAS DEL MÓDULO DE ALQUILERES Y DEVOLUCIONES ---
    Route::get('/alquileres', [AlquilerController::class, 'index'])->name('alquileres');
    Route::post('/alquileres/devolver', [AlquilerController::class, 'procesarDevolucion'])->name('alquileres.devolver');
    Route::post('/alquileres/liberar', [AlquilerController::class, 'procesarLiberacion'])->name('alquileres.liberar');
    Route::get('/alquileres/devolucion/{id}/detalle', [AlquilerController::class, 'detalleDevolucion'])->name('alquileres.devolucion.detalle');
// --- RUTAS  PARA LAS ACCIONES DE RESERVA / ALQUILER ---
Route::get('/reservas/recibo/{id}', [AlquilerController::class, 'verRecibo'])->name('reservas.recibo');
Route::get('/alquileres/entregar/{id}', [AlquilerController::class, 'formEntregarReserva'])->name('reservas.entregar.form');
Route::post('/reservas/cancelar/{id}', [AlquilerController::class, 'cancelarReserva'])->name('reservas.cancelar');

});