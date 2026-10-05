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
    public function __construct()
    {
        // Require Administrator role for all methods in this controller
        $this->middleware('role:Administrador');
    }

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
}
