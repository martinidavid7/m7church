<x-layouts.app :title="$ministry->name">
    <div class="p-6 max-w-3xl">

        <div class="flex items-start justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                @if ($ministry->logo)
                    <img class="h-16 w-16 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-600"
                        src="{{ asset($ministry->logo) }}" alt="{{ $ministry->name }}">
                @else
                    <div class="h-16 w-16 rounded-full bg-indigo-600 flex items-center justify-center ring-2 ring-gray-200 dark:ring-gray-600">
                        <svg class="h-8 w-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                        </svg>
                    </div>
                @endif
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $ministry->name }}</h1>
                    @if ($isLeader)
                        <span class="inline-flex items-center gap-1 text-xs font-medium text-indigo-600 mt-1">
                            Você é líder deste ministério
                        </span>
                    @endif
                </div>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('ministries.schedules.index', $ministry) }}"
                    class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                    Escalas
                </a>
                @if ($isLeader)
                    <a href="{{ route('ministries.edit', $ministry) }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 transition">
                        {{ __('messages.Edit') }}
                    </a>
                @endif
            </div>
        </div>

        @if ($ministry->description)
            <div class="bg-white dark:bg-gray-800 shadow-sm border rounded-xl p-6 mb-6">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">{{ __('messages.Description') }}</h2>
                <p class="text-gray-900 dark:text-white">{{ $ministry->description }}</p>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-gray-800 shadow-sm border rounded-xl p-6">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Líderes</h2>
                @if ($ministry->leaders->isNotEmpty())
                    <ul class="space-y-4">
                        @foreach ($ministry->leaders as $leader)
                            <li>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $leader->name }}</div>
                                @if ($leader->mail)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $leader->mail }}</div>
                                @endif
                                @if ($leader->mobile_phone || $leader->landline_phone)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $leader->mobile_phone ?: $leader->landline_phone }}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-400">Nenhum líder cadastrado.</p>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 shadow-sm border rounded-xl p-6">
                <h2 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-3">Membros</h2>
                @if ($ministry->members->isNotEmpty())
                    <ul class="space-y-4">
                        @foreach ($ministry->members as $member)
                            <li>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $member->name }}</div>
                                @if ($member->mail)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $member->mail }}</div>
                                @endif
                                @if ($member->mobile_phone || $member->landline_phone)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $member->mobile_phone ?: $member->landline_phone }}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-sm text-gray-400">Nenhum membro cadastrado.</p>
                @endif
            </div>
        </div>

        <div class="mt-6">
            @role('Admin|Pastor Presidente|Pastor Auxiliar|Secretaria')
                <a href="{{ route('ministries.index') }}" class="text-sm text-indigo-600 hover:underline">
                    &larr; Voltar para a listagem
                </a>
            @endrole
        </div>

    </div>
</x-layouts.app>
