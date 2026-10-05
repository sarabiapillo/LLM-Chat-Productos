<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;

// Rutas públicas de autenticación para la app móvil (solo clientes)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // Limit to 5 requests per minute against brute force
Route::post('/register', [AuthController::class, 'register']);

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

    // Rutas de Administración (Protegidas por middleware de rol en AdminController)
    Route::get('/admin/users', [AdminController::class, 'listUsers']);
    Route::post('/admin/users/{userId}/role', [AdminController::class, 'assignRole']);
    Route::post('/admin/roles', [AdminController::class, 'createRole']);
    Route::get('/admin/audit-logs', [AdminController::class, 'auditLogs']);
    
    // Cierre de sesión móvil
    Route::post('/logout', [AuthController::class, 'logout']);
});
