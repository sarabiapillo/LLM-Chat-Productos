<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas públicas de autenticación para la app móvil
Route::post('/login', function () {
    return response()->json(['message' => 'Endpoint de login (Pendiente)']);
});
Route::post('/register', function () {
    return response()->json(['message' => 'Endpoint de registro (Pendiente)']);
});

// Rutas protegidas (Requieren token)
Route::middleware('auth:sanctum')->group(function () {
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
    Route::post('/logout', function () {
        return response()->json(['message' => 'Cierre de sesión (Pendiente)']);
    });
});
