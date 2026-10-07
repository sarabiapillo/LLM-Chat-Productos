<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->has('filter') && $request->filter === 'logins') {
            $query->where(function ($q) {
                $q->where('action', 'like', '%Inicio de sesión%')
                  ->orWhere('action', 'like', '%Cierre de sesión%');
            });
        }

        $logs = $query->paginate(20);

        return view('audit.index', compact('logs'));
    }
}
