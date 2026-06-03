<!DOCTYPE html>
@props(['maxWidth' => 'sm:max-w-md'])
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased bg-blue-950">
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative overflow-hidden bg-gradient-to-br from-blue-900 via-blue-800 to-indigo-900 p-4">
            {{-- Decorative circles --}}
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-indigo-600/10 rounded-full blur-3xl"></div>

            {{-- Centered Logo Section --}}
            <div class="relative z-10 flex flex-col items-center mb-6 text-center">
                <div class="bg-white p-3 rounded-2xl shadow-xl shadow-black/10 mb-3 transform hover:scale-105 transition-all duration-300">
                    <a href="/">
                        <img src="{{ asset('images/icon/logo.png') }}" class="h-14 w-auto" alt="Logo">
                    </a>
                </div>
                <h1 class="text-white text-xl font-black tracking-tight">Sistem Seleksi Beasiswa</h1>
                <p class="text-blue-100 text-[10px] mt-0.5 font-bold uppercase tracking-[0.2em]">Fakultas Teknik</p>
            </div>

            {{-- Form Container --}}
            <div class="w-full {{ $maxWidth }} relative z-10">
                <div class="bg-white shadow-2xl shadow-black/20 rounded-[2rem] border border-slate-100 overflow-hidden p-8 sm:p-10">
                    {{ $slot }}
                </div>
            </div>

            {{-- Footer info --}}
            <div class="mt-8 text-center text-xs font-semibold text-blue-200/50 relative z-10">
                &copy; {{ date('Y') }} Fakultas Teknik. All rights reserved.
            </div>
        </div>
    </body>
</html>
