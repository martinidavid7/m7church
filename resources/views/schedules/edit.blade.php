<x-layouts.app :title="'Editar Escala - ' . $ministry->name">
    <div class="p-6 max-w-3xl">
        <a href="{{ route('ministries.schedules.index', $ministry) }}" class="text-sm text-indigo-600 hover:underline">
            &larr; Escalas de {{ $ministry->name }}
        </a>
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mt-1 mb-6">Editar Escala</h1>

        <form action="{{ route('ministries.schedules.update', ['ministry' => $ministry, 'schedule' => $schedule]) }}"
            method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="service_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Reunião</label>
                    <select name="service_id" id="service_id"
                        onchange="document.getElementById('title_field').style.display = this.value ? 'none' : 'block'; document.getElementById('title').required = !this.value;"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">— Evento avulso (sem reunião) —</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}"
                                {{ old('service_id', $schedule->service_id) == $service->id ? 'selected' : '' }}>
                                {{ $service->service }} — {{ $service->day_of_week }}
                                ({{ \Illuminate\Support\Carbon::parse($service->time)->format('H:i') }})
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data *</label>
                    <input type="date" name="date" id="date"
                        value="{{ old('date', $schedule->date->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-6" id="title_field" style="{{ old('service_id', $schedule->service_id) ? 'display: none;' : '' }}">
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Título do evento *</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $schedule->title) }}"
                        placeholder="Ex: Limpeza do salão, Evento de aniversário"
                        {{ old('service_id', $schedule->service_id) ? '' : 'required' }}
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Usado quando a escala não está vinculada a uma reunião cadastrada.</p>
                    @error('title')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                    <textarea name="notes" id="notes" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">{{ old('notes', $schedule->notes) }}</textarea>
                    @error('notes')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            @if ($people->isNotEmpty())
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Escalados</label>
                    <div class="mt-2 space-y-3">
                        @foreach ($people as $person)
                            @php $currentFunction = $currentAssignments[$person->id] ?? $person->pivot->function; @endphp
                            @php $checked = old('people') ? in_array($person->id, old('people', [])) : array_key_exists($person->id, $currentAssignments->toArray()); @endphp
                            <div class="flex items-center gap-3">
                                <input type="checkbox" name="people[]" id="person_{{ $person->id }}"
                                    value="{{ $person->id }}" {{ $checked ? 'checked' : '' }}
                                    onchange="document.getElementById('function_{{ $person->id }}').disabled = !this.checked"
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="person_{{ $person->id }}" class="text-sm text-gray-700 dark:text-gray-300 w-48 truncate">
                                    {{ $person->name }}
                                </label>
                                <input type="text" name="function[{{ $person->id }}]" id="function_{{ $person->id }}"
                                    value="{{ old("function.{$person->id}", $currentFunction) }}" placeholder="Função (ex: Vocal, Teclado)"
                                    {{ $checked ? '' : 'disabled' }}
                                    class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm py-1">
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <p class="text-sm text-gray-400">Este ministério ainda não possui líderes ou membros cadastrados.</p>
            @endif

            <div class="flex gap-3">
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Update') }}
                </button>
                <a href="{{ route('ministries.schedules.index', $ministry) }}"
                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Cancel') }}
                </a>
            </div>
        </form>
    </div>
</x-layouts.app>
