<x-layouts.app :title="__('Dashboard')">
    <div class="p-10">

        <h1 class="text-2xl font-bold mb-8">{{ __('messages.Dashboard') }}</h1>

        <!-- Cards Estatísticos -->
        <div class="grid grid-cols-1 md:grid-cols-{{ $canViewMemberStats ? 4 : 2 }} gap-6 mb-10">

            <div class="bg-white shadow-sm border rounded-xl p-6 text-center">
                <h2 class="text-sm text-slate-500 mb-2">{{ __('messages.People Served') }}</h2>
                <p class="text-lg font-semibold text-indigo-600">
                    {{ __('messages.We Have Served More Than', ['count' => $totalPeopleServed]) }}
                </p>
            </div>

            @if ($canViewMemberStats)
                <div class="bg-white shadow-sm border rounded-xl p-6 text-center">
                    <h2 class="text-sm text-slate-500 mb-2">{{ __('messages.Inactive Members') }}</h2>
                    <p class="text-3xl font-bold text-indigo-600">{{ $inactiveMembers }}</p>
                </div>

                <div class="bg-white shadow-sm border rounded-xl p-6 text-center">
                    <h2 class="text-sm text-slate-500 mb-2">{{ __('messages.Total Members') }}</h2>
                    <p class="text-3xl font-bold text-indigo-600">{{ $totalMembers }}</p>
                </div>
            @endif

            <div class="bg-white shadow-sm border rounded-xl p-6 text-center">
                <h2 class="text-sm text-slate-500 mb-2">{{ __('messages.Services') }}</h2>
                <p class="text-3xl font-bold text-indigo-600">{{ $totalServices }}</p>
            </div>

        </div>

        <!-- Área inferior (gráficos, listagens, etc) -->
        <div class="bg-white shadow-sm border rounded-xl p-8 min-h-[200px]">
            <p class="text-sm text-slate-500">Em breve: gráficos, estatísticas, atividades recentes…</p>
        </div>

    </div>
</x-layouts.app>