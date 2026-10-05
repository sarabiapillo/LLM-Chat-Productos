<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

// Rutas públicas de autenticación para la app móvil (solo clientes)
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1'); // Limit to 5 requests per minute against brute force
Route::post('/register', function () {
    return response()->json(['message' => 'Endpoint de registro (Pendiente)']);
});

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
    
    // Cierre de sesión móvil
    Route::post('/logout', [AuthController::class, 'logout']);
});
