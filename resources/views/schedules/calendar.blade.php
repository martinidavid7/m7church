<x-layouts.app :title="'Escalas - ' . $ministry->name">
    <div class="p-6 max-w-5xl">

        <div class="flex items-center justify-between gap-4 mb-6">
            <div>
                <a href="{{ route('ministries.show', $ministry) }}" class="text-sm text-indigo-600 hover:underline">
                    &larr; {{ $ministry->name }}
                </a>
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mt-1">Escalas</h1>
            </div>

            @if ($isLeader)
                <a href="{{ route('ministries.schedules.create', $ministry) }}"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                    Nova Escala
                </a>
            @endif
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900 p-4 text-sm text-green-700 dark:text-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900 p-4 text-sm text-red-700 dark:text-red-200">
                {{ session('error') }}
            </div>
        @endif

        <p class="text-xs text-gray-400 mb-4">
            Clique em "+ adicionar pessoa" dentro de um dia para escalar alguém, ou no "×" ao lado do nome para remover.
        </p>

        @livewire('ministry-schedule-calendar', ['ministry' => $ministry, 'isLeader' => $isLeader])

    </div>
</x-layouts.app>
