<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(15);
        $roles = Role::where('guard_name', 'web')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,empresa,cliente',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Mapeo a nombre de rol Spatie
        $spatieRole = match($request->role) {
            'admin' => 'Administrador',
            'empresa' => 'Empresa',
            default => 'Cliente',
        };

        if (Role::where('name', $spatieRole)->exists()) {
            $user->syncRoles([$spatieRole]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Crear Usuario (Web)',
            'ip_address' => $request->ip(),
            'details' => "Creó el usuario '{$user->name}' ({$user->email}) con rol {$request->role}",
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:admin,empresa,cliente',
        ]);

        $oldRole = $user->role;
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        $spatieRole = match($request->role) {
            'admin' => 'Administrador',
            'empresa' => 'Empresa',
            default => 'Cliente',
        };

        if (Role::where('name', $spatieRole)->exists()) {
            $user->syncRoles([$spatieRole]);
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Actualizar Usuario (Web)',
            'ip_address' => $request->ip(),
            'details' => "Actualizó usuario '{$user->email}': Rol '{$oldRole}' -> '{$request->role}'",
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy(Request $request, User $user)
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $email = $user->email;
        $user->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Eliminar Usuario (Web)',
            'ip_address' => $request->ip(),
            'details' => "Eliminó el usuario {$email}",
        ]);

        return redirect()->route('users.index')->with('success', 'Usuario eliminado correctamente.');
    }
}
