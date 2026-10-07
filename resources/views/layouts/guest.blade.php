<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'LLM Chat') }}</title>

        <!-- Fonts & Styling -->
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <body class="font-sans text-slate-100 antialiased bg-slate-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
            <div>
                <a href="/" class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-indigo-600 to-purple-600 flex items-center justify-center text-white shadow-lg">
                        <i class="fa-solid fa-robot text-2xl"></i>
                    </div>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-6 bg-slate-900 border border-slate-800 shadow-2xl overflow-hidden sm:rounded-xl text-slate-200">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
