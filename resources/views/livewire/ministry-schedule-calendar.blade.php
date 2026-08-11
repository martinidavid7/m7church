<div class="bg-white dark:bg-gray-800 shadow-sm border rounded-xl p-6">

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

                <div class="space-y-1.5">
                    @foreach ($daySchedules as $daySchedule)
                        <div class="rounded bg-indigo-50 dark:bg-indigo-900 px-1.5 py-1">
                            <div class="flex items-center justify-between gap-1">
                                <div class="font-medium text-indigo-700 dark:text-indigo-200 truncate">
                                    {{ $daySchedule->display_title }}
                                </div>
                                @if ($isLeader)
                                    <div class="flex items-center gap-1 shrink-0">
                                        <a href="{{ route('ministries.schedules.edit', ['ministry' => $ministry, 'schedule' => $daySchedule]) }}"
                                            title="Editar escala" class="text-indigo-400 hover:text-indigo-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                        <button type="button"
                                            x-on:click="window.confirmDeleteSchedule({{ $daySchedule->id }}, {{ Js::from($daySchedule->display_title) }}, $wire)"
                                            title="Excluir escala" class="text-red-400 hover:text-red-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                            </div>

                            <ul class="mt-0.5 space-y-0.5">
                                @foreach ($daySchedule->people as $scheduledPerson)
                                    <li class="flex items-center justify-between gap-1 text-gray-700 dark:text-gray-300">
                                        <span class="truncate">
                                            {{ $scheduledPerson->name }}
                                            @if ($scheduledPerson->pivot->function)
                                                <span class="text-gray-400">({{ $scheduledPerson->pivot->function }})</span>
                                            @endif
                                        </span>
                                        @if ($isLeader)
                                            <button type="button"
                                                x-on:click="window.confirmRemovePerson({{ $daySchedule->id }}, {{ $scheduledPerson->id }}, {{ Js::from($scheduledPerson->name) }}, $wire)"
                                                class="text-red-400 hover:text-red-600 shrink-0">&times;</button>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>

                            @if ($isLeader)
                                @if ($addingToScheduleId === $daySchedule->id)
                                    <div class="mt-1.5 space-y-1 border-t border-indigo-100 dark:border-indigo-800 pt-1.5">
                                        <select wire:model.live="newPersonId" class="w-full text-xs rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white py-1">
                                            <option value="">Selecione...</option>
                                            @foreach ($availablePeople as $person)
                                                <option value="{{ $person->id }}">{{ $person->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" wire:model="newPersonFunction" placeholder="Função (sugerida do cadastro)"
                                            class="w-full text-xs rounded border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white py-1">
                                        <div class="flex gap-1">
                                            <button type="button" wire:click="addPerson"
                                                class="flex-1 text-xs bg-indigo-600 text-white rounded py-1 hover:bg-indigo-700">Adicionar</button>
                                            <button type="button" wire:click="cancelAdding"
                                                class="text-xs px-2 text-gray-500 hover:text-gray-700">Cancelar</button>
                                        </div>
                                    </div>
                                @else
                                    <button type="button" wire:click="startAdding({{ $daySchedule->id }})"
                                        class="mt-1 text-[11px] text-indigo-500 hover:text-indigo-700">
                                        + adicionar pessoa
                                    </button>
                                @endif
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
            @php $day->addDay(); @endphp
        @endwhile
    </div>
</div>
