<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>M7 Church - Gestão de Membros</title>

    <link rel="icon" href="/favicon.ico" sizes="any">
    <link rel="icon" href="/favicon.svg" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/apple-touch-icon.png">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-slate-50 text-slate-900">

    <!-- Navbar -->
    <header class="py-4 px-6 flex justify-between items-center">
        <div class="flex items-center gap-2">
            <div class="h-9 w-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                M7
            </div>
            <span class="font-semibold text-sm">M7 Church</span>
        </div>

        @if (Route::has('login'))
        <nav class="flex items-center gap-4 text-sm">
            @auth
            <a href="{{ url('/dashboard') }}" class="px-4 py-2 border rounded-xl hover:bg-slate-100">
                Dashboard
            </a>
            @else
            <a href="{{ route('login') }}" class="text-slate-700 hover:text-indigo-600">Log in</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}" class="px-4 py-2 border rounded-xl hover:bg-slate-100">
                Register
            </a>
            @endif
            @endauth
        </nav>
        @endif
    </header>

    <!-- Seção principal -->
    <main class="max-w-4xl mx-auto px-6 py-16 text-center">

        <!-- Badge -->
        <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-medium mb-4">
            <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
            App de gestão de membros para igrejas
        </span>

        <!-- Título -->
        <h1 class="text-3xl md:text-5xl font-bold leading-tight mb-6">
            Organize a membresia da sua igreja em um só lugar.
        </h1>

        <!-- Descrição -->
        <p class="text-slate-600 text-base md:text-lg mb-10 leading-relaxed max-w-2xl mx-auto">
            Cadastre membros,  ministérios e pequenos grupos.
            Tudo pensado para o dia a dia da igreja local, de forma simples e acessível
            para pastores, líderes e secretaria.
        </p>

        <!-- CTA -->
        <div class="flex justify-center gap-4 mb-16">
            @auth
            <a href="{{ url('/dashboard') }}"
               class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-md hover:bg-indigo-700">
                Acessar Dashboard
            </a>
            @else
            <a href="{{ route('login') }}"
               class="inline-flex items-center justify-center px-6 py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold shadow-md hover:bg-indigo-700">
                Começar agora
            </a>
            @endauth

            <a href="https://martinisoftware.com.br" target="_blank"
               class="inline-flex items-center justify-center px-5 py-3 rounded-xl border border-slate-300 text-sm text-slate-700 hover:bg-slate-100">
                Saiba mais
            </a>
        </div>

        <!-- Indicadores -->
        <div class="grid grid-cols-3 gap-6 max-w-md mx-auto">
            <div class="bg-white rounded-xl px-4 py-4 shadow-sm">
                <div class="text-xl font-bold text-indigo-600">+{{ $activeMembers }}</div>
                <div class="text-[12px] text-slate-500 uppercase tracking-wide">
                    Membros ativos
                </div>
            </div>

            <div class="bg-white rounded-xl px-4 py-4 shadow-sm">
                <div class="text-xl font-bold text-indigo-600">08</div>
                <div class="text-[12px] text-slate-500 uppercase tracking-wide">
                    Ministérios
                </div>
            </div>

            <div class="bg-white rounded-xl px-4 py-4 shadow-sm">
                <div class="text-xl font-bold text-indigo-600">03</div>
                <div class="text-[12px] text-slate-500 uppercase tracking-wide">
                    Cultos semanais
                </div>
            </div>
        </div>

    </main>

</body>
</html>
