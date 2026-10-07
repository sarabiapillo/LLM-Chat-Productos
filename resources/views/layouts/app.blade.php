<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'LLM Chat Admin') }} - Panel de Control</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome & Tailwind CDN for guaranteed instant aesthetic styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0f172a;
            color: #f8fafc;
            min-height: 100vh;
        }

        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 1rem;
        }

        .glass-card-hover {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .glass-card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
            border-color: rgba(99, 102, 241, 0.3);
        }

        .gradient-text {
            background: linear-gradient(135deg, #818cf8 0%, #c084fc 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .badge-admin {
            background: rgba(239, 68, 68, 0.15);
            color: #fca5a5;
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-soporte {
            background: rgba(245, 158, 11, 0.15);
            color: #fde68a;
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-cliente {
            background: rgba(16, 185, 129, 0.15);
            color: #6ee7b7;
            border: 1px solid rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen flex flex-col">
        <!-- Barra Superior (Top Navigation Component) -->
        @include('layouts.navigation')

        <!-- Mensajes Flash de Éxito / Error -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full mt-4">
            @if(session('success'))
                <div class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-4 py-3 rounded-xl shadow-lg flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-circle-check text-xl"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-emerald-200">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-xl shadow-lg flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <i class="fa-solid fa-triangle-exclamation text-xl"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-rose-200">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-rose-500/10 border border-rose-500/30 text-rose-400 px-4 py-3 rounded-xl shadow-lg mb-4">
                    <div class="font-semibold mb-1"><i class="fa-solid fa-circle-xmark mr-2"></i>Por favor corrige los siguientes errores:</div>
                    <ul class="list-disc list-inside text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Contenido Principal -->
        <main class="flex-grow py-6">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <!-- Pie de página -->
        <footer class="border-t border-slate-800/80 bg-slate-900/50 py-4 text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
                <div>&copy; {{ date('Y') }} LLM Chat Productos. Sistema de Control de Usuarios y Roles.</div>
                <div class="flex items-center space-x-4">
                    <span class="text-emerald-400"><i class="fa-solid fa-shield-halved mr-1"></i> Sesión Segura</span>
                    <span>Laravel Blade v{{ app()->version() }}</span>
                </div>
            </div>
        </footer>
    </div>

    <!-- Live Session Timer Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const timerElement = document.getElementById('live-session-timer');
            if (timerElement) {
                const startTime = parseInt(timerElement.getAttribute('data-start-time') || '0', 10);
                if (startTime > 0) {
                    function updateTimer() {
                        const now = Math.floor(Date.now() / 1000);
                        const elapsed = Math.max(0, now - startTime);

                        const hours = Math.floor(elapsed / 3600);
                        const minutes = Math.floor((elapsed % 3600) / 60);
                        const seconds = elapsed % 60;

                        const pad = (n) => n.toString().padStart(2, '0');
                        timerElement.textContent = `${pad(hours)}:${pad(minutes)}:${pad(seconds)}`;
                    }
                    updateTimer();
                    setInterval(updateTimer, 1000);
                }
            }
        });
    </script>
</body>
</html>
