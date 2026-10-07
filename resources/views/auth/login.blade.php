<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Iniciar Sesión - LLM Chat Admin</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CDN & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: radial-gradient(circle at top right, #1e1b4b 0%, #0f172a 50%, #020617 100%);
            min-height: 100vh;
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.75);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="text-slate-100 flex flex-col min-h-screen">
    
    <!-- Barra Superior Pública con links Iniciar Sesión / Registrarse -->
    @include('layouts.public-navigation')

    <div class="flex-grow flex items-center justify-center p-4 py-12">
        <div class="w-full max-w-md">
            
            <!-- Header / Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 text-white shadow-xl shadow-indigo-500/30 mb-3">
                    <i class="fa-solid fa-robot text-3xl"></i>
                </div>
                <h1 class="font-outfit text-3xl font-bold tracking-tight text-white">LLM Chat Productos</h1>
                <p class="text-slate-400 text-sm mt-1">Acceso seguro con autenticación de roles y auditoría</p>
            </div>

            <!-- Tarjeta de Login -->
            <div class="glass-card rounded-2xl p-8 shadow-2xl">
                
                <!-- Session Status / Errors -->
                @if (session('status'))
                    <div class="mb-4 text-sm text-emerald-400 bg-emerald-500/10 p-3 rounded-lg border border-emerald-500/20">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-6 bg-rose-500/10 border border-rose-500/30 p-4 rounded-xl text-rose-300 text-sm">
                        <div class="font-semibold flex items-center mb-1">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i> Error de autenticación
                        </div>
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Correo Electrónico -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email', 'admin@admin.com') }}" required autofocus
                                   class="w-full pl-10 pr-4 py-3 bg-slate-900/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm">
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password" type="password" name="password" value="password123" required
                                   class="w-full pl-10 pr-4 py-3 bg-slate-900/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all text-sm">
                        </div>
                    </div>

                    <!-- Recuérdame -->
                    <div class="flex items-center justify-between text-sm">
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-indigo-500">
                            <span class="text-slate-400 text-xs">Recordar mi sesión</span>
                        </label>
                    </div>

                    <!-- Botón de Ingreso -->
                    <button type="submit" 
                            class="w-full py-3.5 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-500 hover:to-purple-500 text-white font-semibold rounded-xl shadow-lg shadow-indigo-600/30 transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center space-x-2">
                        <span>Ingresar al Sistema</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <!-- Cuentas de Demostración para Prueba Rápida -->
                <div class="mt-8 pt-6 border-t border-slate-800 text-xs">
                    <div class="text-slate-400 font-semibold mb-2 flex items-center space-x-1.5">
                        <i class="fa-solid fa-key text-indigo-400"></i>
                        <span>Cuentas de Prueba Disponibles (Admin / Empresa / Cliente):</span>
                    </div>
                    <div class="grid grid-cols-1 gap-2">
                        <button type="button" onclick="fillCreds('admin@admin.com', 'password123')" 
                                class="p-2 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 rounded-lg text-left transition-colors flex items-center justify-between group">
                            <div>
                                <span class="text-rose-400 font-bold">Admin:</span>
                                <span class="text-slate-300">admin@admin.com</span>
                            </div>
                            <span class="text-[10px] text-slate-500 group-hover:text-indigo-400">Usar &rarr;</span>
                        </button>

                        <button type="button" onclick="fillCreds('empresa@techcorp.com', 'password123')" 
                                class="p-2 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 rounded-lg text-left transition-colors flex items-center justify-between group">
                            <div>
                                <span class="text-amber-400 font-bold">Empresa:</span>
                                <span class="text-slate-300">empresa@techcorp.com</span>
                            </div>
                            <span class="text-[10px] text-slate-500 group-hover:text-indigo-400">Usar &rarr;</span>
                        </button>

                        <button type="button" onclick="fillCreds('cliente@ejemplo.com', 'password123')" 
                                class="p-2 bg-slate-900/60 hover:bg-slate-800 border border-slate-800 rounded-lg text-left transition-colors flex items-center justify-between group">
                            <div>
                                <span class="text-emerald-400 font-bold">Cliente:</span>
                                <span class="text-slate-300">cliente@ejemplo.com</span>
                            </div>
                            <span class="text-[10px] text-slate-500 group-hover:text-indigo-400">Usar &rarr;</span>
                        </button>
                    </div>
                </div>

            </div>

            <!-- Footer -->
            <p class="text-center text-xs text-slate-500 mt-6">
                &copy; {{ date('Y') }} LLM Chat Productos &bull; Desarrollo Seguro Laravel Blade
            </p>

        </div>
    </div>

    <script>
        function fillCreds(email, pass) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = pass;
        }
    </script>
</body>
</html>
