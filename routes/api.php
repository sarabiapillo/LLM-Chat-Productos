<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\CatalogApiController;
use App\Http\Controllers\Api\N8nSmartFilterController;

// =========================================================================
// RUTAS PÚBLICAS CONSUMIDAS POR LA APP MÓVIL ANDROID (KOTLIN) Y ESCÁNER QR
// =========================================================================
Route::get('/catalogo/{slug}', [CatalogApiController::class, 'getCatalogBySlug']);
Route::get('/q/{slug}', [CatalogApiController::class, 'getCatalogBySlug']);
Route::get('/empresas', [CatalogApiController::class, 'listActiveCompanies']);

// Filtro Inteligente respaldado por n8n en Docker
Route::post('/n8n/smart-filter', [N8nSmartFilterController::class, 'filterCatalog']);

// Rutas públicas de autenticación para la app móvil (clientes con 2FA)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-2fa', [AuthController::class, 'verify2FA']);
Route::post('/resend-2fa', [AuthController::class, 'resend2FA']);

// Rutas protegidas (Requieren JWT de Passport)
Route::middleware('auth:api')->group(function () {
    // Perfil de usuario
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::put('/user/profile', function () {
        return response()->json(['message' => 'Actualizar perfil (Pendiente)']);
    });
    
    // Gestión de conversaciones y mensajes
    Route::get('/conversations', function () {
        return response()->json(['message' => 'Listar conversaciones (Pendiente)']);
    });
    Route::post('/conversations', function () {
        return response()->json(['message' => 'Crear conversación (Pendiente)']);
    });
    Route::get('/conversations/{id}/messages', function () {
        return response()->json(['message' => 'Listar mensajes (Pendiente)']);
    });
    
    // Integración con LLM
    Route::post('/chat/send', function () {
        return response()->json(['message' => 'Enviar mensaje a LLM (Pendiente)']);
    });

    // Rutas de Administración (Protegidas por middleware de rol)
    Route::middleware('role:Administrador')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'listUsers']);
        Route::post('/admin/users', [AdminController::class, 'createUser']);
        Route::put('/admin/users/{userId}', [AdminController::class, 'updateUser']);
        Route::delete('/admin/users/{userId}', [AdminController::class, 'deleteUser']);
        
        Route::post('/admin/users/{userId}/role', [AdminController::class, 'assignRole']);
        Route::post('/admin/roles', [AdminController::class, 'createRole']);
        Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs']);
    });

    // Cierre de sesión móvil
    Route::post('/logout', [AuthController::class, 'logout']);
});
