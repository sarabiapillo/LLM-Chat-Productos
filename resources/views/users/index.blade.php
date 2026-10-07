<x-app-layout>
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-outfit text-2xl font-bold text-white flex items-center space-x-2">
                <i class="fa-solid fa-users text-indigo-400"></i>
                <span>Gestión de Usuarios y Roles (Admin, Empresa, Cliente)</span>
            </h1>
            <p class="text-slate-400 text-sm mt-1">Administra accesos, asigna roles oficiales y gestiona cuentas</p>
        </div>

        <button onclick="document.getElementById('modal-create-user').classList.remove('hidden')" 
                class="px-4 py-2.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold text-xs rounded-xl shadow-lg shadow-indigo-600/30 transition-all flex items-center space-x-2">
            <i class="fa-solid fa-user-plus"></i>
            <span>Crear Nuevo Usuario</span>
        </button>
    </div>

    <!-- Tabla de Usuarios -->
    <div class="glass-card p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-900/80 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5">ID</th>
                        <th class="px-4 py-3.5">Nombre y Correo</th>
                        <th class="px-4 py-3.5">Rol Oficial</th>
                        <th class="px-4 py-3.5">Fecha de Registro</th>
                        <th class="px-4 py-3.5 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-4 text-xs font-mono text-slate-500">#{{ $user->id }}</td>
                            <td class="px-4 py-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-xl bg-indigo-900/60 border border-indigo-700/60 flex items-center justify-center text-indigo-300 font-bold text-sm">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $userRole = $user->role ?? 'cliente';
                                @endphp
                                @if($userRole === 'admin' || $user->hasRole('Administrador'))
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold badge-admin inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-shield-cat text-[10px]"></i>
                                        <span>Administrador</span>
                                    </span>
                                @elseif($userRole === 'empresa' || $user->hasRole('Empresa'))
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30 inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-building text-[10px]"></i>
                                        <span>Empresa</span>
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold badge-cliente inline-flex items-center space-x-1">
                                        <i class="fa-solid fa-user text-[10px]"></i>
                                        <span>Cliente</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-400">
                                {{ $user->created_at ? $user->created_at->format('d/m/Y H:i') : 'N/A' }}
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <button onclick="editUser('{{ $user->id }}', '{{ addslashes($user->name) }}', '{{ addslashes($user->email) }}', '{{ $userRole }}')" 
                                            class="p-2 rounded-lg bg-slate-800 hover:bg-slate-700 text-indigo-400 border border-slate-700 text-xs transition-colors"
                                            title="Editar Rol">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>

                                    @if($user->id !== Auth::id())
                                        <form method="POST" action="{{ route('users.destroy', $user) }}" onsubmit="return confirm('¿Eliminar este usuario?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 text-xs transition-colors" title="Eliminar Usuario">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-500">No hay usuarios registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Modal Crear Usuario -->
    <div id="modal-create-user" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="glass-card max-w-md w-full p-6 shadow-2xl relative">
            <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                <h3 class="font-outfit text-lg font-bold text-white flex items-center space-x-2">
                    <i class="fa-solid fa-user-plus text-indigo-400"></i>
                    <span>Crear Nuevo Usuario</span>
                </h3>
                <button onclick="document.getElementById('modal-create-user').classList.add('hidden')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form method="POST" action="{{ route('users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nombre Completo</label>
                    <input type="text" name="name" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Correo Electrónico</label>
                    <input type="email" name="email" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Contraseña</label>
                    <input type="password" name="password" required minlength="8" class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Rol del Usuario</label>
                    <select name="role" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                        <option value="admin">Administrador (Acceso Total)</option>
                        <option value="empresa">Empresa (Gestión Corporativa)</option>
                        <option value="cliente" selected>Cliente (Usuario Standard)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                    <button type="button" onclick="document.getElementById('modal-create-user').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-700">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-500">
                        Guardar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    <div id="modal-edit-user" class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden">
        <div class="glass-card max-w-md w-full p-6 shadow-2xl relative">
            <div class="flex items-center justify-between mb-4 border-b border-slate-800 pb-3">
                <h3 class="font-outfit text-lg font-bold text-white flex items-center space-x-2">
                    <i class="fa-solid fa-user-pen text-indigo-400"></i>
                    <span>Editar Usuario y Rol</span>
                </h3>
                <button onclick="document.getElementById('modal-edit-user').classList.add('hidden')" class="text-slate-400 hover:text-white">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <form id="form-edit-user" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Nombre Completo</label>
                    <input type="text" id="edit-name" name="name" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Correo Electrónico</label>
                    <input type="email" id="edit-email" name="email" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase mb-1">Rol del Usuario</label>
                    <select id="edit-role" name="role" required class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-white text-sm">
                        <option value="admin">Administrador (Acceso Total)</option>
                        <option value="empresa">Empresa (Gestión Corporativa)</option>
                        <option value="cliente">Cliente (Usuario Standard)</option>
                    </select>
                </div>

                <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-800">
                    <button type="button" onclick="document.getElementById('modal-edit-user').classList.add('hidden')" class="px-4 py-2 bg-slate-800 text-slate-300 text-xs font-semibold rounded-lg hover:bg-slate-700">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold rounded-lg hover:bg-indigo-500">
                        Actualizar Usuario
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editUser(id, name, email, role) {
            document.getElementById('form-edit-user').action = '/usuarios/' + id;
            document.getElementById('edit-name').value = name;
            document.getElementById('edit-email').value = email;
            document.getElementById('edit-role').value = role;
            document.getElementById('modal-edit-user').classList.remove('hidden');
        }
    </script>
</x-app-layout>
