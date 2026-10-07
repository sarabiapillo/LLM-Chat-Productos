<nav class="bg-slate-900/90 border-b border-slate-800/80 sticky top-0 z-50 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Marca y Logotipo -->
            <div class="flex items-center space-x-8">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-200">
                        <i class="fa-solid fa-robot text-lg"></i>
                    </div>
                    <div>
                        <span class="font-outfit text-lg font-bold tracking-tight text-white block leading-none">LLM Chat</span>
                        <span class="text-[10px] text-indigo-400 font-semibold tracking-wider uppercase block mt-1">Panel de Gestión</span>
                    </div>
                </a>

                <!-- Navegación Principal -->
                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('dashboard') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2 {{ request()->routeIs('dashboard') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="fa-solid fa-chart-line text-xs"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('chat.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2 {{ request()->routeIs('chat.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="fa-solid fa-robot text-xs text-indigo-400"></i>
                        <span>Chat LLM</span>
                    </a>

                    <a href="{{ route('users.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2 {{ request()->routeIs('users.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="fa-solid fa-users text-xs"></i>
                        <span>Usuarios</span>
                    </a>

                    <a href="{{ route('clients.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2 {{ request()->routeIs('clients.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="fa-solid fa-address-book text-xs"></i>
                        <span>Clientes</span>
                    </a>

                    <a href="{{ route('audit.index') }}" 
                       class="px-3 py-2 rounded-lg text-sm font-medium transition-colors flex items-center space-x-2 {{ request()->routeIs('audit.*') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                        <i class="fa-solid fa-clock-rotate-left text-xs"></i>
                        <span>Log de Ingresos</span>
                    </a>
                </div>
            </div>

            <!-- Sección Derecha: Timer de Sesión + Roles (admin / empresa / cliente) + Logout -->
            <div class="flex items-center space-x-4">
                @php
                    $sessionStart = session('login_time', now()->timestamp);
                    $userRole = Auth::user()->role ?? 'cliente';
                @endphp
                
                <!-- Timer de Sesión Activa -->
                <div class="hidden lg:flex items-center space-x-2 px-3 py-1.5 rounded-full bg-slate-800/80 border border-slate-700/60 text-xs font-mono text-indigo-300 shadow-inner" title="Tiempo transcurrido en la sesión actual">
                    <i class="fa-solid fa-stopwatch text-indigo-400 animate-pulse"></i>
                    <span class="text-slate-400">Sesión:</span>
                    <span id="live-session-timer" data-start-time="{{ $sessionStart }}" class="font-bold text-emerald-400">00:00:00</span>
                </div>

                <!-- Badges para los 3 Roles Oficiales (admin / empresa / cliente) -->
                <div>
                    @if($userRole === 'admin' || Auth::user()->hasRole('Administrador'))
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold badge-admin flex items-center space-x-1">
                            <i class="fa-solid fa-shield-cat text-[10px]"></i>
                            <span>Admin</span>
                        </span>
                    @elseif($userRole === 'empresa' || Auth::user()->hasRole('Empresa'))
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-500/15 text-amber-300 border border-amber-500/30 flex items-center space-x-1">
                            <i class="fa-solid fa-building text-[10px]"></i>
                            <span>Empresa</span>
                        </span>
                    @else
                        <span class="px-2.5 py-1 rounded-md text-xs font-semibold badge-cliente flex items-center space-x-1">
                            <i class="fa-solid fa-user text-[10px]"></i>
                            <span>Cliente</span>
                        </span>
                    @endif
                </div>

                <!-- Perfil y Cierre de Sesión -->
                <div class="flex items-center space-x-3 pl-2 border-l border-slate-800">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-semibold text-white leading-none">{{ Auth::user()->name }}</div>
                        <div class="text-xs text-slate-400 mt-0.5">{{ Auth::user()->email }}</div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" 
                                class="p-2 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 border border-rose-500/30 hover:border-rose-500/50 text-xs font-medium transition-all flex items-center space-x-1.5 group"
                                title="Cerrar sesión e invalidar acceso">
                            <i class="fa-solid fa-right-from-bracket group-hover:translate-x-0.5 transition-transform"></i>
                            <span class="hidden md:inline">Salir</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        <!-- Menú Móvil -->
        <div class="md:hidden flex items-center justify-around py-2 border-t border-slate-800 text-xs">
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <i class="fa-solid fa-chart-line block text-center mb-0.5"></i> Inicio
            </a>
            <a href="{{ route('chat.index') }}" class="{{ request()->routeIs('chat.*') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <i class="fa-solid fa-robot block text-center mb-0.5"></i> LLM
            </a>
            <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <i class="fa-solid fa-users block text-center mb-0.5"></i> Usuarios
            </a>
            <a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <i class="fa-solid fa-address-book block text-center mb-0.5"></i> Clientes
            </a>
            <a href="{{ route('audit.index') }}" class="{{ request()->routeIs('audit.*') ? 'text-indigo-400 font-bold' : 'text-slate-400' }}">
                <i class="fa-solid fa-clock-rotate-left block text-center mb-0.5"></i> Logs
            </a>
        </div>
    </div>
</nav>
