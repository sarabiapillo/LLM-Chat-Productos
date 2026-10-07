<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->paginate(15);
        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'status' => 'required|in:Activo,Inactivo,Prospecto',
            'notes' => 'nullable|string',
        ]);

        $client = Client::create($request->all());

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Crear Cliente (Web)',
            'ip_address' => $request->ip(),
            'details' => "Registró el cliente '{$client->name}' ({$client->email})",
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente registrado exitosamente.');
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:50',
            'company' => 'nullable|string|max:255',
            'status' => 'required|in:Activo,Inactivo,Prospecto',
            'notes' => 'nullable|string',
        ]);

        $client->update($request->all());

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Actualizar Cliente (Web)',
            'ip_address' => $request->ip(),
            'details' => "Actualizó la información del cliente '{$client->name}'",
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    public function destroy(Request $request, Client $client)
    {
        $name = $client->name;
        $client->delete();

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => 'Eliminar Cliente (Web)',
            'ip_address' => $request->ip(),
            'details' => "Eliminó al cliente '{$name}'",
        ]);

        return redirect()->route('clients.index')->with('success', 'Cliente eliminado correctamente.');
    }
}
