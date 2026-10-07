<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\AuditLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Registrar tiempo de inicio de sesión en session
        $loginTimestamp = now()->timestamp;
        $request->session()->put('login_time', $loginTimestamp);

        // Registrar log de auditoría de ingreso
        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Inicio de sesión',
            'ip_address' => $request->ip(),
            'details' => "Ingreso al sistema web Blade como '{$user->name}' (" . ($user->role ?? 'usuario') . ")",
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        $loginTime = $request->session()->get('login_time');
        
        $durationText = 'Desconocida';
        if ($loginTime) {
            $seconds = max(0, now()->timestamp - $loginTime);
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $secs = $seconds % 60;
            $durationText = sprintf('%02dh %02dm %02ds (%d seg)', $hours, $minutes, $secs, $seconds);
        }

        if ($user) {
            // Registrar log de auditoría de salida
            AuditLog::create([
                'user_id' => $user->id,
                'action' => 'Cierre de sesión',
                'ip_address' => $request->ip(),
                'details' => "Salida del sistema web Blade (Duración de sesión: {$durationText})",
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
