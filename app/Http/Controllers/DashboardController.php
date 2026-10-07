<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $currentUser = Auth::user();
        
        // Métricas principales
        $totalUsers = User::count();
        $totalClients = Client::count();
        $activeClients = Client::where('status', 'Activo')->count();
        $loginsToday = AuditLog::where('action', 'like', '%Inicio de sesión%')
            ->whereDate('created_at', now()->today())
            ->count();

        // Logs recientes de acceso
        $recentLogs = AuditLog::with('user')->latest()->take(10)->get();

        // Lista de usuarios recientes
        $recentUsers = User::latest()->take(5)->get();

        // Lista de clientes recientes
        $recentClients = Client::latest()->take(5)->get();

        return view('dashboard', compact(
            'currentUser',
            'totalUsers',
            'totalClients',
            'activeClients',
            'loginsToday',
            'recentLogs',
            'recentUsers',
            'recentClients'
        ));
    }
}
