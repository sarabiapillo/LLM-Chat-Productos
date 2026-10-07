<nav class="bg-slate-900/90 border-b border-slate-800/80 sticky top-0 z-50 backdrop-blur-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            
            <!-- Logotipo y Marca Publica -->
            <a href="/" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg shadow-indigo-500/30 group-hover:scale-105 transition-transform duration-200">
                    <i class="fa-solid fa-robot text-lg"></i>
                </div>
                <div>
                    <span class="font-outfit text-lg font-bold tracking-tight text-white block leading-none">LLM Chat</span>
                    <span class="text-[10px] text-indigo-400 font-semibold tracking-wider uppercase block mt-1">Productos & IA</span>
                </div>
            </a>

            <!-- Accesos de la Barra Superior Pública -->
            <div class="flex items-center space-x-3">
                <a href="{{ route('login') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5 {{ request()->routeIs('login') ? 'bg-indigo-600/20 text-indigo-400 border border-indigo-500/30' : 'text-slate-300 hover:text-white hover:bg-slate-800/60' }}">
                    <i class="fa-solid fa-right-to-bracket text-xs"></i>
                    <span>Iniciar Sesión</span>
                </a>

                <a href="{{ route('register') }}" 
                   class="px-4 py-2 rounded-xl text-xs font-semibold transition-all flex items-center space-x-1.5 {{ request()->routeIs('register') ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'bg-emerald-600/20 text-emerald-400 border border-emerald-500/30 hover:bg-emerald-600 hover:text-white' }}">
                    <i class="fa-solid fa-user-plus text-xs"></i>
                    <span>Registrarse</span>
                    <span class="text-[9px] uppercase px-1.5 py-0.5 rounded bg-emerald-900/80 text-emerald-200 border border-emerald-500/40 ml-1">Cliente</span>
                </a>
            </div>

        </div>
    </div>
</nav>
