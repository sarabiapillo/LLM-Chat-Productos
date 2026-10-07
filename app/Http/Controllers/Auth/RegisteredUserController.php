<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     * En el registro público SOLO se asigna el rol de CLIENTE por seguridad.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // Asignación estricta de Rol CLIENTE para el registro público
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'cliente',
        ]);

        if (Role::where('name', 'Cliente')->exists()) {
            $user->assignRole('Cliente');
        }

        event(new Registered($user));

        Auth::login($user);

        // Guardar timestamp de inicio de sesión y registrar log de auditoría
        $request->session()->put('login_time', now()->timestamp);

        AuditLog::create([
            'user_id' => $user->id,
            'action' => 'Inicio de sesión (Nuevo Registro Cliente)',
            'ip_address' => $request->ip(),
            'details' => "Registro público de nuevo Cliente '{$user->name}' ({$user->email})",
        ]);

        return redirect(route('dashboard', absolute: false));
    }
}
