<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Catálogo - {{ $company->name }}</title>

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
            background: #090d16;
            color: #f1f5f9;
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
    </style>
</head>
<body class="min-h-screen pb-12">

    <!-- Encabezado de la Empresa (Escaneo QR) -->
    <header class="bg-slate-900 border-b border-slate-800 py-6 px-4 sticky top-0 z-40 backdrop-blur-md">
        <div class="max-w-md mx-auto flex items-center space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white font-bold text-2xl shadow-lg flex-shrink-0">
                @if($company->logo_url)
                    <img src="{{ $company->logo_url }}" alt="{{ $company->name }}" class="w-full h-full object-cover rounded-2xl">
                @else
                    {{ strtoupper(substr($company->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <h1 class="font-outfit text-xl font-bold text-white leading-tight">{{ $company->name }}</h1>
                <p class="text-xs text-indigo-400 mt-0.5"><i class="fa-solid fa-qrcode mr-1"></i> Catálogo Digital Oficial</p>
                <div class="text-[11px] text-slate-400 mt-1 flex items-center space-x-3">
                    @if($company->phone)
                        <span><i class="fa-solid fa-phone text-slate-500 mr-1"></i>{{ $company->phone }}</span>
                    @endif
                    <span><i class="fa-solid fa-envelope text-slate-500 mr-1"></i>{{ $company->email }}</span>
                </div>
            </div>
        </div>
    </header>

    <!-- Contenido del Catálogo Móvil -->
    <main class="max-w-md mx-auto px-4 mt-6 space-y-6">

        <!-- Banner Informativo -->
        <div class="p-3.5 rounded-xl bg-indigo-500/10 border border-indigo-500/30 text-indigo-300 text-xs flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-mobile-screen-button text-base"></i>
                <span>Disponible también en la App Móvil Android (Kotlin)</span>
            </div>
            <span class="px-2 py-0.5 bg-indigo-600 text-white font-bold text-[10px] rounded uppercase">API Active</span>
        </div>

        <!-- Categorías y Productos -->
        @forelse($categories as $category)
            @if($category->products->isNotEmpty())
                <div>
                    <h2 class="font-outfit text-sm font-bold text-slate-300 uppercase tracking-wider mb-3 flex items-center space-x-2">
                        <i class="fa-solid fa-tag text-indigo-400"></i>
                        <span>{{ $category->name }}</span>
                    </h2>

                    <div class="space-y-3">
                        @foreach($category->products as $product)
                            <div class="glass-card p-4 rounded-xl flex items-center justify-between">
                                <div class="pr-3">
                                    <h3 class="font-semibold text-white text-base">{{ $product->name }}</h3>
                                    @if($product->description)
                                        <p class="text-xs text-slate-400 mt-1">{{ $product->description }}</p>
                                    @endif
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="px-3 py-1.5 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-mono font-bold text-sm block">
                                        ${{ number_format($product->price, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @empty
        @endforelse

        <!-- Productos sin categoría -->
        @if($uncategorizedProducts->isNotEmpty())
            <div>
                <h2 class="font-outfit text-sm font-bold text-slate-300 uppercase tracking-wider mb-3 flex items-center space-x-2">
                    <i class="fa-solid fa-box text-indigo-400"></i>
                    <span>Otros Productos</span>
                </h2>

                <div class="space-y-3">
                    @foreach($uncategorizedProducts as $product)
                        <div class="glass-card p-4 rounded-xl flex items-center justify-between">
                            <div class="pr-3">
                                <h3 class="font-semibold text-white text-base">{{ $product->name }}</h3>
                                @if($product->description)
                                    <p class="text-xs text-slate-400 mt-1">{{ $product->description }}</p>
                                @endif
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="px-3 py-1.5 rounded-lg bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 font-mono font-bold text-sm block">
                                    ${{ number_format($product->price, 2) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @if($categories->isEmpty() && $uncategorizedProducts->isEmpty())
            <div class="glass-card p-8 text-center rounded-2xl text-slate-500 text-sm">
                <i class="fa-solid fa-box-open text-3xl mb-2 text-slate-600 block"></i>
                Esta empresa aún no tiene productos registrados en su catálogo.
            </div>
        @endif

    </main>

    <footer class="max-w-md mx-auto text-center text-xs text-slate-600 mt-12 px-4">
        &copy; {{ date('Y') }} Plataforma SaaS Multi-Empresa &bull; Catálogo Digital QR
    </footer>

</body>
</html>
