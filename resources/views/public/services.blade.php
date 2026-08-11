<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Reuniões - M7 Church</title>

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
        <a href="{{ url('/') }}" class="flex items-center gap-2">
            <div class="h-9 w-9 rounded-xl bg-indigo-600 text-white flex items-center justify-center font-bold">
                M7
            </div>
            <span class="font-semibold text-sm">M7 Church</span>
        </a>

        @if (Route::has('login'))
        <nav class="flex items-center gap-4 text-sm">
            @auth
            <a href="{{ url('/dashboard') }}" class="px-4 py-2 border rounded-xl hover:bg-slate-100">
                Dashboard
            </a>
            @else
            <a href="{{ route('login') }}" class="text-slate-700 hover:text-indigo-600">Log in</a>
            @endauth
        </nav>
        @endif
    </header>

    <!-- Seção principal -->
    <main class="max-w-4xl mx-auto px-6 py-16">

        <div class="text-center mb-12">
            <span class="inline-flex items-center gap-2 rounded-full bg-indigo-50 text-indigo-700 px-3 py-1 text-xs font-medium mb-4">
                <span class="h-1.5 w-1.5 rounded-full bg-indigo-500"></span>
                Venha nos visitar
            </span>

            <h1 class="text-3xl md:text-4xl font-bold leading-tight mb-4">
                Nossas Reuniões
            </h1>

            <p class="text-slate-600 text-base md:text-lg leading-relaxed max-w-2xl mx-auto">
                Confira os dias e horários de nossos cultos, células e demais reuniões. Você é muito bem-vindo!
            </p>
        </div>

        @if ($services->isEmpty())
            <div class="bg-white rounded-xl px-6 py-10 shadow-sm text-center text-slate-500">
                Nenhuma reunião cadastrada no momento.
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                @foreach ($services as $service)
                    <div class="bg-white rounded-xl px-6 py-5 shadow-sm border border-slate-100">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2 class="font-semibold text-slate-900">{{ $service->service }}</h2>
                                @if ($service->serviceType)
                                    <span class="text-[12px] text-indigo-600 uppercase tracking-wide font-medium">
                                        {{ $service->serviceType->service_type }}
                                    </span>
                                @endif
                            </div>
                            <div class="text-right shrink-0">
                                <div class="text-sm font-semibold text-slate-900">{{ $service->day_of_week }}</div>
                                <div class="text-sm text-slate-500">
                                    {{ $service->time ? \Illuminate\Support\Carbon::parse($service->time)->format('H:i') : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="text-center mt-12">
            <a href="{{ url('/') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                &larr; Voltar para a página inicial
            </a>
        </div>

    </main>

</body>
</html>
