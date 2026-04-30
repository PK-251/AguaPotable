<?php

use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Agua\ComprobanteController;
use App\Http\Controllers\Agua\EgresoController;
use App\Http\Controllers\Agua\ImportExportController;
use App\Http\Controllers\Agua\MultaController;
use App\Http\Controllers\Agua\MultaUsuarioController;
use App\Http\Controllers\Agua\PadronController;
use App\Http\Controllers\Agua\PagoController;
use App\Http\Controllers\Agua\ReporteMensualController;
use App\Http\Controllers\Agua\TarifaController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'create'])->name('home');
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::post('logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');

    Route::middleware('admin')->group(function () {
        Route::get('auditoria', [AuditLogController::class, 'index'])->name('audit.index');
        Route::get('usuarios', [UserManagementController::class, 'index'])->name('users.index');
    });
});

Route::prefix('agua')->middleware('auth')->name('agua.')->group(function () {
    Route::resource('padron', PadronController::class)->except(['destroy']);

    Route::get('tarifas', [TarifaController::class, 'index'])->name('tarifas.index');
    Route::middleware('admin')->group(function () {
        Route::get('tarifas/create', [TarifaController::class, 'create'])->name('tarifas.create');
        Route::post('tarifas', [TarifaController::class, 'store'])->name('tarifas.store');
    });
    Route::get('tarifas/{tarifa}', [TarifaController::class, 'show'])->name('tarifas.show');
    Route::middleware('admin')->group(function () {
        Route::get('tarifas/{tarifa}/edit', [TarifaController::class, 'edit'])->name('tarifas.edit');
        Route::put('tarifas/{tarifa}', [TarifaController::class, 'update'])->name('tarifas.update');
    });

    Route::get('multas', [MultaController::class, 'index'])->name('multas.index');
    Route::middleware('admin')->group(function () {
        Route::get('multas/create', [MultaController::class, 'create'])->name('multas.create');
        Route::post('multas', [MultaController::class, 'store'])->name('multas.store');
    });
    Route::get('multas/{multa}', [MultaController::class, 'show'])->name('multas.show');
    Route::middleware('admin')->group(function () {
        Route::get('multas/{multa}/edit', [MultaController::class, 'edit'])->name('multas.edit');
        Route::put('multas/{multa}', [MultaController::class, 'update'])->name('multas.update');
    });

    Route::get('multas-usuario', [MultaUsuarioController::class, 'index'])->name('multas-usuario.index');
    Route::get('multas-usuario/aplicar', [MultaUsuarioController::class, 'create'])->name('multas-usuario.create');
    Route::post('multas-usuario', [MultaUsuarioController::class, 'store'])->name('multas-usuario.store');

    Route::get('cobros', [PagoController::class, 'index'])->name('cobros.index');
    Route::post('cobros', [PagoController::class, 'store'])->name('cobros.store');
    Route::get('egresos', [EgresoController::class, 'index'])->name('egresos.index');
    Route::get('reportes-mensuales', [ReporteMensualController::class, 'index'])->name('reportes-mensuales.index');
    Route::get('import-export/padron', [ImportExportController::class, 'padron'])->name('import-export.padron');
    Route::get('comprobantes/{pago}', [ComprobanteController::class, 'show'])->name('comprobantes.show');
});
