<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class LlmChatController extends Controller
{
    public function index()
    {
        $models = [
            ['id' => 'gemini-1.5-pro', 'name' => 'Gemini 1. Pro (Google)', 'badge' => 'Recomendado', 'icon' => 'fa-brands fa-google'],
            ['id' => 'gpt-4o', 'name' => 'GPT-4o (OpenAI)', 'badge' => 'Alta Precisión', 'icon' => 'fa-solid fa-brain'],
            ['id' => 'claude-3-5-sonnet', 'name' => 'Claude 3.5 Sonnet (Anthropic)', 'badge' => 'Razonamiento', 'icon' => 'fa-solid fa-feather'],
            ['id' => 'llama-3-70b', 'name' => 'Llama 3 70B (Meta Open-Source)', 'badge' => 'Open Source', 'icon' => 'fa-solid fa-microchip'],
        ];

        return view('chat.index', compact('models'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'prompt' => 'required|string|max:4000',
        ]);

        $prompt = $request->prompt;
        $model = $request->model;

        // Respuesta interactiva simulada del Modelo LLM
        $responseContent = $this->generateLlmReply($prompt, $model);

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => "Consulta LLM ({$model})",
            'ip_address' => $request->ip(),
            'details' => "Promt enviado: '" . \Illuminate\Support\Str::limit($prompt, 50) . "'",
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'model' => $model,
                'response' => $responseContent,
                'timestamp' => now()->format('H:i:s'),
            ]);
        }

        return redirect()->route('chat.index')->with('llm_reply', $responseContent);
    }

    private function generateLlmReply(string $prompt, string $model): string
    {
        $user = Auth::user();
        $roleName = match($user->role) {
            'admin' => 'Administrador',
            'empresa' => 'Empresa Corporativa',
            default => 'Cliente Standard',
        };

        return "🤖 **[Respuesta de {$model}]**\n\nHola **{$user->name}** (Rol: *{$roleName}*). He procesado tu solicitud:\n\n> \"{$prompt}\"\n\n✨ *Análisis del Modelo:* Tu consulta fue procesada correctamente en la plataforma LLM Chat Productos. Todas las iteraciones y métricas se encuentran respaldadas en la base de datos MySQL.";
    }
}
