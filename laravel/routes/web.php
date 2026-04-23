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
use App\Http\Controllers\Portal\PortalComprobanteController;
use App\Http\Controllers\Portal\PortalEstadoCuentaController;
use App\Http\Controllers\Portal\PortalHomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'create'])->name('login');
    Route::post('login', [LoginController::class, 'store'])->name('login.store');
});

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('auditoria', [AuditLogController::class, 'index'])->name('audit.index');
    Route::get('usuarios', [UserManagementController::class, 'index'])->name('users.index');
});

Route::prefix('agua')->middleware('auth')->name('agua.')->group(function () {
    Route::get('padron', [PadronController::class, 'index'])->name('padron.index');
    Route::get('tarifas', [TarifaController::class, 'index'])->name('tarifas.index');
    Route::get('multas', [MultaController::class, 'index'])->name('multas.index');
    Route::get('multas-usuario', [MultaUsuarioController::class, 'index'])->name('multas-usuario.index');
    Route::get('cobros', [PagoController::class, 'index'])->name('cobros.index');
    Route::get('egresos', [EgresoController::class, 'index'])->name('egresos.index');
    Route::get('reportes-mensuales', [ReporteMensualController::class, 'index'])->name('reportes-mensuales.index');
    Route::get('import-export/padron', [ImportExportController::class, 'padron'])->name('import-export.padron');
    Route::get('comprobantes/{pago}', [ComprobanteController::class, 'show'])->name('comprobantes.show');
});

Route::prefix('portal')->name('portal.')->group(function () {
    Route::get('/', PortalHomeController::class)->name('home');
    Route::get('estado-cuenta', PortalEstadoCuentaController::class)->name('estado-cuenta');
    Route::get('comprobantes', [PortalComprobanteController::class, 'index'])->name('comprobantes.index');
});
