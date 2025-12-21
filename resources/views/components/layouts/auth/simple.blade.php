<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'M7 Church') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-slate-50 text-slate-900">

        <!-- Topo -->
        <header class="py-4 px-6 flex justify-between items-center">
            <a href="{{ route('home') }}" class="flex items-center gap-2" wire:navigate>
                <div class="h-9 w-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                    M7
                </div>
                <span class="font-semibold text-sm">M7 Church</span>
            </a>

            @if (Route::currentRouteName() === 'login')
                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="text-sm text-indigo-600 hover:underline" wire:navigate>Criar conta</a>
                @endif
            @else
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:underline" wire:navigate>Fazer login</a>
                @endif
            @endif
        </header>

        <!-- Conteúdo -->
        <main>
            {{ $slot }}
        </main>

        @livewireScripts
    </body>
</html>
