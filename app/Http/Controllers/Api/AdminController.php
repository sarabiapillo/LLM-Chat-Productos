<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{

    public function listUsers()
    {
        return response()->json(User::with('roles')->get());
    }

    public function assignRole(Request $request, $userId)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::findOrFail($userId);
        // Sync roles replaces previous roles with the new one
        $user->syncRoles([$request->role]);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Asignar Rol',
            'ip_address' => $request->ip(),
            'details' => "Asignó el rol {$request->role} al usuario ID {$user->id}"
        ]);

        return response()->json(['message' => 'Rol asignado exitosamente']);
    }

    public function createRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name'
        ]);

        $role = Role::create(['name' => $request->name, 'guard_name' => 'api']);
        
        if ($request->has('permissions')) {
            $role->givePermissionTo($request->permissions);
        }

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Crear Rol',
            'ip_address' => $request->ip(),
            'details' => "Creó el rol {$role->name}"
        ]);

        return response()->json(['message' => 'Rol creado exitosamente', 'role' => $role], 201);
    }

    public function auditLogs()
    {
        return response()->json(AuditLog::latest()->get());
    }

    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|exists:roles,name'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Hash::make($request->password),
        ]);
        
        $user->assignRole($request->role);

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Crear Usuario',
            'ip_address' => $request->ip(),
            'details' => "Creó al usuario {$user->email} con rol {$request->role}"
        ]);

        return response()->json(['message' => 'Usuario creado', 'user' => $user->load('roles')], 201);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,'.$id,
            'role' => 'sometimes|required|string|exists:roles,name'
        ]);

        $user->update($request->only(['name', 'email']));

        if ($request->has('role')) {
            $user->syncRoles([$request->role]);
        }

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Actualizar Usuario',
            'ip_address' => $request->ip(),
            'details' => "Actualizó al usuario {$user->email}"
        ]);

        return response()->json(['message' => 'Usuario actualizado', 'user' => $user->load('roles')]);
    }

    public function deleteUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        if ($user->id === $request->user()->id) {
            return response()->json(['message' => 'No puedes eliminarte a ti mismo'], 400);
        }

        $user->delete();

        AuditLog::create([
            'user_id' => $request->user()->id,
            'action' => 'Eliminar Usuario',
            'ip_address' => $request->ip(),
            'details' => "Eliminó al usuario ID {$id}"
        ]);

        return response()->json(['message' => 'Usuario eliminado correctamente']);
    }
}
