<div class="bg-white dark:bg-gray-800 shadow-sm border rounded-xl p-6" x-data="{ openScheduleId: @entangle('openScheduleId') }">

    {{-- Navegação de mês --}}
    <div class="flex items-center justify-between mb-4">
        <button wire:click="previousMonth" type="button" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white capitalize">
            {{ $reference->translatedFormat('F \d\e Y') }}
        </h2>
        <button wire:click="nextMonth" type="button" class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
            <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
            </svg>
        </button>
    </div>

    {{-- Grade do calendário --}}
    @php
        $startOfMonth = $reference->copy()->startOfMonth();
        $endOfMonth = $reference->copy()->endOfMonth();
        $startOfGrid = $startOfMonth->copy()->startOfWeek(\Carbon\Carbon::SUNDAY);
        $endOfGrid = $endOfMonth->copy()->endOfWeek(\Carbon\Carbon::SATURDAY);
        $weekDays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'];
        $allSchedules = $schedules->flatten(1);
    @endphp

    <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden text-xs">
        @foreach ($weekDays as $weekDay)
            <div class="bg-gray-50 dark:bg-gray-700 text-center py-2 font-medium text-gray-500 dark:text-gray-300">
                {{ $weekDay }}
            </div>
        @endforeach

        @php $day = $startOfGrid->copy(); @endphp
        @while ($day->lte($endOfGrid))
            @php
                $isCurrentMonth = $day->month === $reference->month;
                $daySchedules = $schedules->get($day->toDateString(), collect());
            @endphp
            <div class="bg-white dark:bg-gray-800 min-h-[110px] p-1.5 align-top {{ $isCurrentMonth ? '' : 'opacity-40' }}">
                <div class="flex items-center justify-between mb-1">
                    <span class="text-gray-500 dark:text-gray-400">{{ $day->day }}</span>
                    @if ($isLeader && $isCurrentMonth)
                        <a href="{{ route('ministries.schedules.create', ['ministry' => $ministry, 'date' => $day->toDateString()]) }}"
                            title="Nova escala neste dia"
                            class="text-indigo-500 hover:text-indigo-700 leading-none">+</a>
                    @endif
                </div>

                <div class="space-y-1">
                    @foreach ($daySchedules as $daySchedule)
                        @php
                            $iAmScheduled = $currentPersonId && $daySchedule->people->contains('id', $currentPersonId);
                            $peopleCount = $daySchedule->people->count();
                        @endphp
                        <button type="button" wire:click="openSchedule({{ $daySchedule->id }})"
                            class="w-full text-left rounded px-1.5 py-1 transition {{ $iAmScheduled ? 'bg-indigo-100 dark:bg-indigo-800 ring-1 ring-indigo-400' : 'bg-indigo-50 dark:bg-indigo-900 hover:bg-indigo-100 dark:hover:bg-indigo-800' }}">
                            <div class="font-medium text-indigo-700 dark:text-indigo-200 truncate">
                                {{ $daySchedule->display_title }}
                            </div>
                            <div class="flex items-center justify-between text-[10px] text-indigo-500 dark:text-indigo-300">
                                <span>{{ $peopleCount }} {{ Str::plural('pessoa', $peopleCount) }}</span>
                                @if ($iAmScheduled)
                                    <span class="font-semibold">Você está aqui</span>
                                @endif
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
            @php $day->addDay(); @endphp
        @endwhile
    </div>

    {{-- Modal de detalhes da escala --}}
    <template x-if="openScheduleId">
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4" style="background: rgba(0,0,0,0.5)"
            x-on:click.self="$wire.closeSchedule()">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl max-w-4xl w-full max-h-[85vh] overflow-y-auto">
                @foreach ($allSchedules as $daySchedule)
                    <div x-show="openScheduleId === {{ $daySchedule->id }}" class="p-6">
                        <div class="flex items-start justify-between gap-4 mb-1">
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                    {{ $daySchedule->display_title }}
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    {{ $daySchedule->date->translatedFormat('d/m/Y (l)') }}
                                    @if ($daySchedule->service)
                                        &middot; {{ \Illuminate\Support\Carbon::parse($daySchedule->service->time)->format('H:i') }}
                                    @endif
                                </p>
                            </div>
                            <button type="button" wire:click="closeSchedule" class="text-gray-400 hover:text-gray-600 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        @if ($daySchedule->notes)
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-2 mb-4">{{ $daySchedule->notes }}</p>
                        @endif

                        @if ($isLeader)
                            <div class="flex items-center gap-4 text-sm mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                                <a href="{{ route('ministries.schedules.edit', ['ministry' => $ministry, 'schedule' => $daySchedule]) }}"
                                    class="text-indigo-600 hover:text-indigo-800">Editar escala</a>
                                <button type="button"
                                    x-on:click="window.confirmDeleteSchedule({{ $daySchedule->id }}, {{ Js::from($daySchedule->display_title) }}, $wire)"
                                    class="text-red-600 hover:text-red-800">Excluir escala</button>
                            </div>
                        @endif

                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Escalados ({{ $daySchedule->people->count() }})
                        </h4>

                        <ul class="space-y-2">
                            @foreach ($daySchedule->people as $scheduledPerson)
                                @php $isMe = $currentPersonId === $scheduledPerson->id; @endphp
                                <li class="flex items-center justify-between gap-2 text-sm {{ $isMe ? 'font-semibold text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300' }}">
                                    <span>
                                        {{ $scheduledPerson->name }}
                                        @if ($isMe)
                                            <span class="text-xs font-medium">(Você)</span>
                                        @endif
                                        @if ($scheduledPerson->pivot->function)
                                            <span class="text-gray-400 font-normal">— {{ $scheduledPerson->pivot->function }}</span>
                                        @endif
                                    </span>
                                    @if ($isLeader)
                                        <button type="button"
                                            x-on:click="window.confirmRemovePerson({{ $daySchedule->id }}, {{ $scheduledPerson->id }}, {{ Js::from($scheduledPerson->name) }}, $wire)"
                                            class="text-red-400 hover:text-red-600 shrink-0">&times;</button>
                                    @endif
                                </li>
                            @endforeach
                            @if ($daySchedule->people->isEmpty())
                                <li class="text-sm text-gray-400">Ninguém escalado ainda.</li>
                            @endif
                        </ul>

                        @if ($isLeader)
                            @if ($addingToScheduleId === $daySchedule->id)
                                <div class="mt-4 space-y-2 border-t border-gray-100 dark:border-gray-700 pt-4">
                                    <select wire:model.live="newPersonId" class="w-full text-sm rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white py-1.5">
                                        <option value="">Selecione...</option>
                                        @foreach ($availablePeople as $person)
                                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" wire:model="newPersonFunction" placeholder="Função (sugerida do cadastro)"
                                        class="w-full text-sm rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white py-1.5">
                                    <div class="flex gap-2">
                                        <button type="button" wire:click="addPerson"
                                            class="flex-1 text-sm bg-indigo-600 text-white rounded py-1.5 hover:bg-indigo-700">Adicionar</button>
                                        <button type="button" wire:click="cancelAdding"
                                            class="text-sm px-3 text-gray-500 hover:text-gray-700">Cancelar</button>
                                    </div>
                                </div>
                            @else
                                <button type="button" wire:click="startAdding({{ $daySchedule->id }})"
                                    class="mt-4 text-sm text-indigo-500 hover:text-indigo-700">
                                    + adicionar pessoa
                                </button>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    </template>
</div>
