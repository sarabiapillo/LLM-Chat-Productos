<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class CatalogApiController extends Controller
{
    /**
     * Endpoint público consumido por la App Móvil Android (Kotlin) o al escanear el QR.
     * Devuelve el catálogo completo de productos y precios aislados por empresa.
     */
    public function getCatalogBySlug(Request $request, string $slug)
    {
        $company = Company::where('slug', $slug)
            ->where('status', 'Activo')
            ->first();

        if (!$company) {
            return response()->json([
                'status' => 'error',
                'message' => 'Empresa o catálogo no encontrado o inactivo.'
            ], 404);
        }

        // Cargar categorías con sus productos activos
        $categories = $company->categories()
            ->with(['products' => function ($q) {
                $q->where('is_active', true)->orderBy('name', 'asc');
            }])
            ->get();

        // Productos sin categoría asociada
        $uncategorizedProducts = $company->products()
            ->whereNull('category_id')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        // Registrar lectura en log de auditoría
        AuditLog::create([
            'user_id' => null,
            'action' => 'Consulta API Catálogo Kotlin',
            'ip_address' => $request->ip(),
            'details' => "App Android/QR consultó catálogo de '{$company->name}' (Slug: {$slug})",
        ]);

        return response()->json([
            'status' => 'success',
            'empresa' => [
                'id' => $company->id,
                'nombre' => $company->name,
                'slug' => $company->slug,
                'email' => $company->email,
                'telefono' => $company->phone,
                'logo_url' => $company->logo_url,
                'qr_url' => $company->qr_url,
            ],
            'categorias' => $categories,
            'productos_sin_categoria' => $uncategorizedProducts,
            'total_productos' => $company->products()->where('is_active', true)->count(),
            'timestamp' => now()->toDateTimeString(),
        ], 200);
    }

    /**
     * Directorio público de empresas disponibles (para la App Móvil Kotlin)
     */
    public function listActiveCompanies(Request $request)
    {
        $companies = Company::where('status', 'Activo')
            ->select('id', 'name', 'slug', 'email', 'phone', 'logo_url', 'plan')
            ->get();

        return response()->json([
            'status' => 'success',
            'empresas' => $companies,
            'total' => $companies->count(),
        ]);
    }
}
