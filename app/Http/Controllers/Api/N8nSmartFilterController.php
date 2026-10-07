<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\AuditLog;
use App\Services\N8nService;
use Illuminate\Http\Request;

class N8nSmartFilterController extends Controller
{
    protected N8nService $n8nService;

    public function __construct(N8nService $n8nService)
    {
        $this->n8nService = $n8nService;
    }

    /**
     * Endpoint de Filtro Inteligente respaldado por n8n en Docker.
     * Consumido por la App Móvil Android (Kotlin) para búsquedas inteligentes de productos.
     */
    public function filterCatalog(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|exists:companies,slug',
            'query' => 'required|string|max:255',
        ]);

        $searchQuery = (string) $request->input('query');
        $company = Company::where('slug', $request->slug)->firstOrFail();

        // Obtener productos activos de la empresa
        $products = $company->products()
            ->where('is_active', true)
            ->select('id', 'name', 'description', 'price')
            ->get()
            ->toArray();

        // Enviar a n8n en Docker para filtrado IA
        $filteredResult = $this->n8nService->applySmartFilter($products, $searchQuery);

        // Registrar auditoría de filtro inteligente
        AuditLog::create([
            'user_id' => null,
            'action' => 'Filtro Inteligente n8n (Kotlin API)',
            'ip_address' => $request->ip(),
            'details' => "Búsqueda IA '{$searchQuery}' en empresa '{$company->name}'",
        ]);

        return response()->json([
            'status' => 'success',
            'empresa' => $company->name,
            'slug' => $company->slug,
            'busqueda' => $searchQuery,
            'resultado_n8n' => $filteredResult,
        ]);
    }
}
