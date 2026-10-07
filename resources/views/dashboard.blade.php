<x-app-layout>
    <!-- Banner de Bienvenida -->
    <div class="glass-card p-6 md:p-8 mb-8 relative overflow-hidden">
        <!-- Decoración de fondo -->
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-indigo-600/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute right-40 -top-10 w-48 h-48 bg-purple-600/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 p-0.5 shadow-xl shadow-indigo-500/20">
                    <div class="w-full h-full bg-slate-900 rounded-[14px] flex items-center justify-center text-indigo-400 font-bold text-2xl">
                        {{ strtoupper(substr($currentUser->name, 0, 1)) }}
                    </div>
                </div>
                <div>
                    <div class="flex items-center space-x-3">
                        <h1 class="font-outfit text-2xl md:text-3xl font-bold text-white tracking-tight">
                            ¡Bienvenido al Panel, <span class="gradient-text">{{ $currentUser->name }}</span>!
                        </h1>
                    </div>
                    <p class="text-slate-400 text-sm mt-1 flex items-center space-x-2">
                        <span><i class="fa-solid fa-envelope text-slate-500 mr-1"></i>{{ $currentUser->email }}</span>
                        <span>&bull;</span>
                        <span><i class="fa-solid fa-calendar-day text-slate-500 mr-1"></i>{{ now()->isoFormat('D [de] MMMM, YYYY') }}</span>
                    </p>
                </div>
            </div>

            <!-- Botones de Acción Rápida -->
            <div class="flex items-center space-x-3 w-full md:w-auto">
                <a href="{{ route('users.index') }}" class="flex-1 md:flex-none px-4 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-semibold rounded-xl shadow-lg shadow-indigo-600/20 transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Nuevo Usuario</span>
                </a>
                <a href="{{ route('clients.index') }}" class="flex-1 md:flex-none px-4 py-2.5 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-200 text-xs font-semibold rounded-xl transition-all flex items-center justify-center space-x-2">
                    <i class="fa-solid fa-address-book"></i>
                    <span>Nuevo Cliente</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Métricas Principales (Grid de 4 Tarjetas) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Total Usuarios -->
        <div class="glass-card p-6 glass-card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Usuarios</p>
                    <h3 class="font-outfit text-3xl font-bold text-white mt-1">{{ $totalUsers }}</h3>
                    <p class="text-xs text-indigo-400 mt-2 flex items-center">
                        <i class="fa-solid fa-users-gear mr-1"></i> Registrados en sistema
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400">
                    <i class="fa-solid fa-users text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Clientes -->
        <div class="glass-card p-6 glass-card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Clientes Activos</p>
                    <h3 class="font-outfit text-3xl font-bold text-white mt-1">{{ $activeClients }} <span class="text-sm font-normal text-slate-500">/ {{ $totalClients }}</span></h3>
                    <p class="text-xs text-emerald-400 mt-2 flex items-center">
                        <i class="fa-solid fa-building-user mr-1"></i> {{ $totalClients > 0 ? round(($activeClients / $totalClients) * 100) : 0 }}% activos
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400">
                    <i class="fa-solid fa-address-card text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Inicios de Sesión Hoy -->
        <div class="glass-card p-6 glass-card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ingresos Hoy</p>
                    <h3 class="font-outfit text-3xl font-bold text-white mt-1">{{ $loginsToday }}</h3>
                    <p class="text-xs text-amber-400 mt-2 flex items-center">
                        <i class="fa-solid fa-right-to-bracket mr-1"></i> Sesiones iniciadas
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/20 flex items-center justify-center text-amber-400">
                    <i class="fa-solid fa-key text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Tiempo de Sesión Activa -->
        <div class="glass-card p-6 glass-card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Tu Tiempo en Sesión</p>
                    <h3 class="font-outfit text-2xl font-bold text-indigo-300 mt-1 font-mono">
                        <span id="live-session-timer-card" class="text-emerald-400">00:00:00</span>
                    </h3>
                    <p class="text-xs text-purple-400 mt-2 flex items-center">
                        <i class="fa-solid fa-stopwatch mr-1"></i> Monitoreo en tiempo real
                    </p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-purple-500/10 border border-purple-500/20 flex items-center justify-center text-purple-400">
                    <i class="fa-solid fa-clock text-xl animate-spin" style="animation-duration: 12s;"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Secciones Principales (Tablas de Usuarios, Clientes y Logs Recientes) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Columna Izquierda: Usuarios Recientes & Clientes -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Tabla Usuarios Recientes -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="font-outfit text-lg font-bold text-white flex items-center space-x-2">
                            <i class="fa-solid fa-users text-indigo-400 text-base"></i>
                            <span>Usuarios del Sistema</span>
                        </h2>
                        <p class="text-xs text-slate-400">Cuentas registradas y asignación de roles</p>
                    </div>
                    <a href="{{ route('users.index') }}" class="text-xs text-indigo-400 hover:text-indigo-300 font-semibold flex items-center space-x-1">
                        <span>Ver Todos</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-900/60 border-b border-slate-800">
                            <tr>
                                <th class="px-4 py-3">Usuario</th>
                                <th class="px-4 py-3">Rol</th>
                                <th class="px-4 py-3">Registro</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($recentUsers as $user)
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="px-4 py-3 font-medium text-white flex items-center space-x-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-900/50 border border-indigo-700/50 flex items-center justify-center text-indigo-300 text-xs font-bold">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-semibold">{{ $user->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if(($user->role ?? '') === 'admin' || $user->hasRole('Administrador'))
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold badge-admin">Admin</span>
                                        @elseif(($user->role ?? '') === 'soporte' || $user->hasRole('Editor'))
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold badge-soporte">Soporte</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold badge-cliente">Cliente</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-xs text-slate-400">
                                        {{ $user->created_at ? $user->created_at->diffForHumans() : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-slate-500">No hay usuarios registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tabla Clientes Recientes -->
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="font-outfit text-lg font-bold text-white flex items-center space-x-2">
                            <i class="fa-solid fa-address-book text-emerald-400 text-base"></i>
                            <span>Clientes Recientes</span>
                        </h2>
                        <p class="text-xs text-slate-400">Empresas y clientes de la plataforma</p>
                    </div>
                    <a href="{{ route('clients.index') }}" class="text-xs text-emerald-400 hover:text-emerald-300 font-semibold flex items-center space-x-1">
                        <span>Ver Todos</span>
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="text-xs text-slate-400 uppercase bg-slate-900/60 border-b border-slate-800">
                            <tr>
                                <th class="px-4 py-3">Cliente / Empresa</th>
                                <th class="px-4 py-3">Contacto</th>
                                <th class="px-4 py-3">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($recentClients as $client)
                                <tr class="hover:bg-slate-800/40 transition-colors">
                                    <td class="px-4 py-3 font-medium text-white">
                                        <div class="font-semibold">{{ $client->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $client->company ?? 'Particular' }}</div>
                                    </td>
                                    <td class="px-4 py-3 text-xs text-slate-400">
                                        <div>{{ $client->email }}</div>
                                        <div class="text-slate-500">{{ $client->phone ?? 'Sin teléfono' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($client->status === 'Activo')
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Activo</span>
                                        @elseif($client->status === 'Prospecto')
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-500/20 text-amber-300 border border-amber-500/30">Prospecto</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-700 text-slate-400 border border-slate-600">Inactivo</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-4 text-center text-slate-500">No hay clientes registrados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

        <!-- Columna Derecha: Stream de Bitácora / Log de Ingreso de Usuarios -->
        <div class="space-y-6">
            <div class="glass-card p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="font-outfit text-lg font-bold text-white flex items-center space-x-2">
                            <i class="fa-solid fa-clock-rotate-left text-purple-400 text-base"></i>
                            <span>Log de Ingresos y Salidas</span>
                        </h2>
                        <p class="text-xs text-slate-400">Historial de entradas, salidas y duraciones</p>
                    </div>
                    <a href="{{ route('audit.index') }}" class="text-xs text-purple-400 hover:text-purple-300 font-semibold">
                        Ver Bitácora
                    </a>
                </div>

                <!-- Lista de Eventos de Log -->
                <div class="space-y-4">
                    @forelse($recentLogs as $log)
                        <div class="p-3.5 rounded-xl bg-slate-900/70 border border-slate-800 flex items-start space-x-3 text-xs">
                            <div class="mt-0.5">
                                @if(str_contains($log->action, 'Inicio de sesión'))
                                    <div class="w-7 h-7 rounded-full bg-emerald-500/20 border border-emerald-500/40 text-emerald-400 flex items-center justify-center">
                                        <i class="fa-solid fa-right-to-bracket text-[10px]"></i>
                                    </div>
                                @elseif(str_contains($log->action, 'Cierre de sesión'))
                                    <div class="w-7 h-7 rounded-full bg-rose-500/20 border border-rose-500/40 text-rose-400 flex items-center justify-center">
                                        <i class="fa-solid fa-right-from-bracket text-[10px]"></i>
                                    </div>
                                @else
                                    <div class="w-7 h-7 rounded-full bg-indigo-500/20 border border-indigo-500/40 text-indigo-400 flex items-center justify-center">
                                        <i class="fa-solid fa-bolt text-[10px]"></i>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-grow min-w-0">
                                <div class="flex items-center justify-between">
                                    <span class="font-semibold text-slate-200 truncate">
                                        {{ $log->user ? $log->user->name : 'Usuario #' . $log->user_id }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 ml-2">
                                        {{ $log->created_at ? $log->created_at->format('H:i:s') : '' }}
                                    </span>
                                </div>
                                <div class="text-indigo-300 font-medium mt-0.5">{{ $log->action }}</div>
                                @if($log->details)
                                    <div class="text-slate-400 mt-1 text-[11px] bg-slate-950/50 p-1.5 rounded border border-slate-800/80 font-mono break-all">
                                        {{ Str::limit($log->details, 80) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-slate-500 text-xs">
                            No hay registros de auditoría recientes.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>

    <!-- Dual Sync Script for Card Timer -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const timerCard = document.getElementById('live-session-timer-card');
            const timerNav = document.getElementById('live-session-timer');
            if (timerCard && timerNav) {
                function syncCard() {
                    timerCard.textContent = timerNav.textContent;
                }
                syncCard();
                setInterval(syncCard, 1000);
            }
        });
    </script>
</x-app-layout>
