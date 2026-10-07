<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Registro de Cuenta Cliente - LLM Chat</title>
    
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
    
    <!-- Barra Superior Pública -->
    @include('layouts.public-navigation')

    <div class="flex-grow flex items-center justify-center p-4 py-12">
        <div class="w-full max-w-md">
            
            <div class="text-center mb-6">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 inline-flex items-center space-x-1.5 mb-2">
                    <i class="fa-solid fa-user-check"></i>
                    <span>Registro Oficial de Cuenta Cliente</span>
                </span>
                <h1 class="font-outfit text-3xl font-bold tracking-tight text-white">Crea tu Cuenta</h1>
                <p class="text-slate-400 text-sm mt-1">Acceso inmediato como Cliente a la plataforma LLM Chat</p>
            </div>

            <!-- Tarjeta de Registro -->
            <div class="glass-card rounded-2xl p-8 shadow-2xl">
                
                @if ($errors->any())
                    <div class="mb-6 bg-rose-500/10 border border-rose-500/30 p-4 rounded-xl text-rose-300 text-sm">
                        <div class="font-semibold flex items-center mb-1">
                            <i class="fa-solid fa-triangle-exclamation mr-2"></i> Error en el registro
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    <!-- Campo Oculto / Fijo para el Rol Cliente -->
                    <input type="hidden" name="role" value="cliente">

                    <!-- Nombre Completo -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                            Nombre Completo
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-user"></i>
                            </div>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm"
                                   placeholder="Juan Pérez">
                        </div>
                    </div>

                    <!-- Correo Electrónico -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                            Correo Electrónico
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm"
                                   placeholder="cliente@ejemplo.com">
                        </div>
                    </div>

                    <!-- Contraseña -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                            Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-lock"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Confirmar Contraseña -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">
                            Confirmar Contraseña
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                                <i class="fa-solid fa-check-double"></i>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-900/80 border border-slate-700/80 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-all text-sm"
                                   placeholder="••••••••">
                        </div>
                    </div>

                    <!-- Nota de Asignación de Rol -->
                    <div class="p-3 bg-slate-900/60 border border-slate-800 rounded-xl text-xs text-slate-400 flex items-center space-x-2">
                        <i class="fa-solid fa-shield-halved text-emerald-400 text-base flex-shrink-0"></i>
                        <span>Tu cuenta se registrará automáticamente con el rol exclusivo de <strong>Cliente</strong>.</span>
                    </div>

                    <!-- Botón de Registro -->
                    <button type="submit" 
                            class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-semibold rounded-xl shadow-lg shadow-emerald-600/30 transition-all duration-200 transform hover:-translate-y-0.5 flex items-center justify-center space-x-2">
                        <span>Completar Registro</span>
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </button>
                </form>

                <div class="mt-6 text-center text-xs text-slate-400">
                    ¿Ya tienes una cuenta? 
                    <a href="{{ route('login') }}" class="text-indigo-400 hover:text-indigo-300 font-semibold underline ml-1">Inicia sesión aquí</a>
                </div>

            </div>

            <p class="text-center text-xs text-slate-500 mt-6">
                &copy; {{ date('Y') }} LLM Chat Productos &bull; Autenticación Segura
            </p>

        </div>
    </div>

</body>
</html>
