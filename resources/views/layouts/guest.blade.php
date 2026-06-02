<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Timeline') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-slate-900 dark:text-zinc-100 antialiased bg-slate-50 dark:bg-zinc-950 min-h-screen flex flex-col justify-center relative overflow-hidden px-4 transition-colors duration-300">
        <!-- Floating decorative blobs for visual depth -->
        <div class="absolute top-[-10%] left-[-10%] w-[350px] sm:w-[500px] h-[350px] sm:h-[500px] rounded-full bg-violet-400/20 dark:bg-violet-600/10 blur-[80px] sm:blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[350px] sm:w-[500px] h-[350px] sm:h-[500px] rounded-full bg-indigo-400/20 dark:bg-indigo-600/10 blur-[80px] sm:blur-[120px] pointer-events-none"></div>
        
        <div class="min-h-screen flex flex-col sm:justify-center items-center py-12 sm:py-0 z-10 relative">
            <div class="flex flex-col items-center">
                <a href="/" class="flex flex-col items-center group">
                    <div class="p-3 bg-white dark:bg-zinc-900 rounded-2xl shadow-xl border border-slate-100 dark:border-zinc-800/80 group-hover:scale-105 group-hover:shadow-indigo-500/10 transition-all duration-300">
                        <img src="{{ asset('images/timeline.png') }}" class="w-12 h-12 sm:w-16 sm:h-16 object-contain" alt="Timeline Logo">
                    </div>
                    <span class="mt-4 text-xl sm:text-2xl font-extrabold tracking-tight bg-gradient-to-r from-violet-600 to-indigo-600 dark:from-violet-400 dark:to-indigo-400 bg-clip-text text-transparent group-hover:opacity-90 transition-opacity">
                        {{ config('app.name', 'Timeline') }}
                    </span>
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-8 px-6 py-8 sm:px-8 bg-white/70 dark:bg-zinc-900/70 backdrop-blur-xl border border-slate-200/50 dark:border-zinc-800/80 shadow-2xl overflow-hidden rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>

