<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class N8nService
{
    protected string $webhookUrl;

    public function __construct()
    {
        $this->webhookUrl = config('services.n8n.webhook_url', env('N8N_WEBHOOK_URL', 'http://127.0.0.1:5678/webhook/smart-filter'));
    }

    /**
     * Envía la consulta del cliente al nodo IA de n8n para aplicar un filtro inteligente de catálogo.
     */
    public function applySmartFilter(array $products, string $query): array
    {
        try {
            $response = Http::timeout(10)->post($this->webhookUrl, [
                'query' => $query,
                'productos' => $products,
                'timestamp' => now()->toDateTimeString(),
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning("n8n Webhook returned status: " . $response->status());
        } catch (\Exception $e) {
            Log::error("Error conectando con n8n Docker: " . $e->getMessage());
        }

        // Fallback local en caso de que n8n no esté respondiendo en desarrollo
        return [
            'status' => 'fallback',
            'filtro_inteligente' => 'Filtro por coincidencia de texto',
            'query' => $query,
            'productos_recomendados' => array_values(array_filter($products, function ($p) use ($query) {
                return empty($query) || str_contains(strtolower($p['name']), strtolower($query));
            })),
        ];
    }
}
