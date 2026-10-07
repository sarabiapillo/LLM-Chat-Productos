<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\AuditLogController;
use App\Http\Controllers\LlmChatController;
use App\Http\Controllers\QrCatalogController;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::get('/admin', function () {
    return redirect()->route('dashboard');
});

// Rutas Públicas de Escaneo de QR para Clientes
Route::get('/q/{slug}', [QrCatalogController::class, 'show'])->name('qr.show');
Route::get('/catalogo/{slug}', [QrCatalogController::class, 'show'])->name('catalog.show');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestión de Usuarios
    Route::get('/usuarios', [UserController::class, 'index'])->name('users.index');
    Route::post('/usuarios', [UserController::class, 'store'])->name('users.store');
    Route::put('/usuarios/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/usuarios/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    // Gestión de Clientes / Empresas
    Route::get('/clientes', [ClientController::class, 'index'])->name('clients.index');
    Route::post('/clientes', [ClientController::class, 'store'])->name('clients.store');
    Route::put('/clientes/{client}', [ClientController::class, 'update'])->name('clients.update');
    Route::delete('/clientes/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');

    // Bitácora / Log de Ingreso de Usuarios
    Route::get('/log-ingresos', [AuditLogController::class, 'index'])->name('audit.index');

    // Asistente y Chat LLM
    Route::get('/chat', [LlmChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [LlmChatController::class, 'send'])->name('chat.send');
});

require __DIR__.'/auth.php';
