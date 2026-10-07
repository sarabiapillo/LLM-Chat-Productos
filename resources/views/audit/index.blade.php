<x-app-layout>
    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="font-outfit text-2xl font-bold text-white flex items-center space-x-2">
                <i class="fa-solid fa-clock-rotate-left text-purple-400"></i>
                <span>Log de Ingresos y Auditoría del Sistema</span>
            </h1>
            <p class="text-slate-400 text-sm mt-1">Bitácora inmutable de inicios de sesión, cierres de sesión, direcciones IP y duraciones</p>
        </div>

        <!-- Filtros de Auditoría -->
        <div class="flex items-center space-x-2">
            <a href="{{ route('audit.index') }}" 
               class="px-3 py-2 rounded-xl text-xs font-semibold border transition-all {{ !request('filter') ? 'bg-indigo-600/20 text-indigo-300 border-indigo-500/40' : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-white' }}">
                Todos los Eventos
            </a>
            <a href="{{ route('audit.index', ['filter' => 'logins']) }}" 
               class="px-3 py-2 rounded-xl text-xs font-semibold border transition-all {{ request('filter') === 'logins' ? 'bg-indigo-600/20 text-indigo-300 border-indigo-500/40' : 'bg-slate-900 border-slate-800 text-slate-400 hover:text-white' }}">
                Solo Ingresos y Salidas
            </a>
        </div>
    </div>

    <!-- Tabla de Log de Ingresos -->
    <div class="glass-card p-6">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-300">
                <thead class="text-xs text-slate-400 uppercase bg-slate-900/80 border-b border-slate-800">
                    <tr>
                        <th class="px-4 py-3.5">ID Log</th>
                        <th class="px-4 py-3.5">Usuario</th>
                        <th class="px-4 py-3.5">Acción Realizada</th>
                        <th class="px-4 py-3.5">Dirección IP</th>
                        <th class="px-4 py-3.5">Detalles / Duración de Sesión</th>
                        <th class="px-4 py-3.5">Fecha y Hora</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/80">
                    @forelse($logs as $log)
                        <tr class="hover:bg-slate-800/40 transition-colors">
                            <td class="px-4 py-4 text-xs font-mono text-slate-500">#{{ $log->id }}</td>
                            <td class="px-4 py-4 font-medium text-white">
                                @if($log->user)
                                    <div class="flex items-center space-x-2">
                                        <div class="w-7 h-7 rounded-lg bg-indigo-900/60 border border-indigo-700/60 flex items-center justify-center text-indigo-300 font-bold text-xs">
                                            {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold text-slate-200">{{ $log->user->name }}</div>
                                            <div class="text-[11px] text-slate-500">{{ $log->user->email }}</div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-slate-500 italic">Usuario ID #{{ $log->user_id ?? 'Sistema' }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @if(str_contains($log->action, 'Inicio de sesión'))
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 inline-flex items-center space-x-1.5">
                                        <i class="fa-solid fa-right-to-bracket text-[10px]"></i>
                                        <span>Inicio de Sesión</span>
                                    </span>
                                @elseif(str_contains($log->action, 'Cierre de sesión'))
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-500/20 text-rose-300 border border-rose-500/30 inline-flex items-center space-x-1.5">
                                        <i class="fa-solid fa-right-from-bracket text-[10px]"></i>
                                        <span>Cierre de Sesión</span>
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 inline-flex items-center space-x-1.5">
                                        <i class="fa-solid fa-bolt text-[10px]"></i>
                                        <span>{{ $log->action }}</span>
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-xs font-mono text-slate-400">
                                <i class="fa-solid fa-network-wired text-slate-600 mr-1"></i>
                                {{ $log->ip_address ?? '127.0.0.1' }}
                            </td>
                            <td class="px-4 py-4 text-xs font-mono text-slate-300 max-w-sm">
                                <div class="bg-slate-900/80 p-2 rounded-lg border border-slate-800 break-words">
                                    {{ $log->details }}
                                </div>
                            </td>
                            <td class="px-4 py-4 text-xs text-slate-400">
                                <div><i class="fa-regular fa-clock text-slate-500 mr-1"></i>{{ $log->created_at ? $log->created_at->format('d/m/Y H:i:s') : 'N/A' }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $log->created_at ? $log->created_at->diffForHumans() : '' }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-slate-500">No hay registros de auditoría disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
