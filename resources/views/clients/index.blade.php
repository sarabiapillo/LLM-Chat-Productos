<x-app-layout>
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-outfit text-2xl font-bold text-white flex items-center space-x-2">
                <i class="fa-solid fa-address-book text-emerald-400"></i>
                <span>Gestión de Clientes</span>
            </h1>
            <p class="text-slate-400 text-sm mt-1">Directorio de empresas, contactos y cuentas corporativas de clientes</p>
        </div>

        <button onclick="document.getElementById('modal-create-client').classList.remove('hidden')" 
                class="px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-emerald-600/30 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-plus"></i>
            <span>Registrar Nuevo Cliente</span>
        </button>
    </div>

    <!-- Tabla de Clientes -->
    <div class="glass-card p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-900/80 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5">Cliente / Empresa</th>
                        <th class="px-4 py-3.5">Correo y Teléfono</th>
                        <th class="px-4 py-3.5">Estado</th>
                        <th class="px-4 py-3.5">Notas</th>
                        <th class="px-4 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($clients as $client)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-4 font-medium text-white">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-emerald-900/60 border border-emerald-700/60 flex items-center justify-center text-emerald-300 font-bold text-sm">
                                        <i class="fa-solid fa-building"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white">{{ $client->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $client->company ?? 'Particular' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-300">
                                <div><i class="fa-solid fa-envelope text-slate-500 mr-1.5"></i>{{ $client->email }}</div>
                                <div class="text-slate-400 mt-0.5"><i class="fa-solid fa-phone text-slate-500 mr-1.5"></i>{{ $client->phone ?? 'Sin teléfono' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                @if($client->status === 'Activo')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-circle text-[8px]"></i>
                                        <span>Activo</span>
                                    </span>
                                @elseif($client->status === 'Prospecto')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-circle text-[8px]"></i>
                                        <span>Prospecto</span>
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-800 text-slate-400 border border-slate-700 inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-circle text-[8px]"></i>
                                        <span>Inactivo</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-400 max-w-xs truncate">
                                {{ $client->notes ?? 'Sin observaciones' }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="editClient('{{ $client->id }}', '{{ addslashes($client->name) }}', '{{ addslashes($client->email) }}', '{{ addslashes($client->phone) }}', '{{ addslashes($client->company) }}', '{{ $client->status }}', '{{ addslashes($client->notes) }}')" 
                                            class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-emerald-400 border border-slate-700 text-xs transition-colors"
                                            title="Editar Cliente">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    <form method="POST" action="{{ route('clients.destroy', $client) }}" onsubmit="return confirm('¿Eliminar cliente?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs transition-colors" title="Eliminar Cliente">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">No hay clientes registrados en el sistema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $clients->links() }}
        </div>
    </div>

    <!-- Modal Crear Cliente -->
    <div id="modal-create-client" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="glass-card max-w-md w-full p-6 shadow-2xl relative">
            <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                <h3 class="font-outfit text-lg font-bold text-white flex items-center space-x-2">
                    <i class="fa-solid fa-address-card text-emerald-400"></i>
                    <span>Registrar Cliente</span>
                </h3>
                <button onclick="document.getElementById('modal-create-client').classList.add('hidden')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('clients.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nombre Completo / Contacto</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Correo Electrónico</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Empresa</label>
                        <input type="text" name="company" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Teléfono</label>
                        <input type="text" name="phone" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Estado</label>
                    <select name="status" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                        <option value="Activo" selected>Activo</option>
                        <option value="Prospecto">Prospecto</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Notas / Observaciones</label>
                    <textarea name="notes" rows="2" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                    <button type="button" onclick="document.getElementById('modal-create-client').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-700">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-500">
                        Guardar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Cliente -->
    <div id="modal-edit-client" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="glass-card max-w-md w-full p-6 shadow-2xl relative">
            <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                <h3 class="font-outfit text-lg font-bold text-white flex items-center space-x-2">
                    <i class="fa-solid fa-pen-to-square text-emerald-400"></i>
                    <span>Editar Cliente</span>
                </h3>
                <button onclick="document.getElementById('modal-edit-client').classList.add('hidden')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="form-edit-client" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nombre Completo / Contacto</label>
                    <input type="text" id="edit-client-name" name="name" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Correo Electrónico</label>
                    <input type="email" id="edit-client-email" name="email" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Empresa</label>
                        <input type="text" id="edit-client-company" name="company" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Teléfono</label>
                        <input type="text" id="edit-client-phone" name="phone" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Estado</label>
                    <select id="edit-client-status" name="status" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                        <option value="Activo">Activo</option>
                        <option value="Prospecto">Prospecto</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Notas / Observaciones</label>
                    <textarea id="edit-client-notes" name="notes" rows="2" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm"></textarea>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                    <button type="button" onclick="document.getElementById('modal-edit-client').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-700">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white text-xs font-semibold rounded-lg hover:bg-emerald-500">
                        Actualizar Cliente
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editClient(id, name, email, phone, company, status, notes) {
            document.getElementById('form-edit-client').action = '/clientes/' + id;
            document.getElementById('edit-client-name').value = name;
            document.getElementById('edit-client-email').value = email;
            document.getElementById('edit-client-phone').value = phone;
            document.getElementById('edit-client-company').value = company;
            document.getElementById('edit-client-status').value = status;
            document.getElementById('edit-client-notes').value = notes;
            document.getElementById('modal-edit-client').classList.remove('hidden');
        }
    </script>
</x-app-layout>
