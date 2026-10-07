<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class QrCatalogController extends Controller
{
    /**
     * Muestra la vista optimizada para celular cuando el cliente escanea el QR de la Empresa.
     */
    public function show(Request $request, string $slug)
    {
        $company = Company::where('slug', $slug)
            ->where('status', 'Activo')
            ->firstOrFail();

        $categories = $company->categories()
            ->with(['products' => function ($q) {
                $q->where('is_active', true)->orderBy('name', 'asc');
            }])
            ->get();

        $uncategorizedProducts = $company->products()
            ->whereNull('category_id')
            ->where('is_active', true)
            ->orderBy('name', 'asc')
            ->get();

        AuditLog::create([
            'user_id' => null,
            'action' => 'Escaneo QR Catálogo Web',
            'ip_address' => $request->ip(),
            'details' => "Cliente escaneó el QR de la empresa '{$company->name}'",
        ]);

        return view('catalog.show', compact('company', 'categories', 'uncategorizedProducts'));
    }
}
