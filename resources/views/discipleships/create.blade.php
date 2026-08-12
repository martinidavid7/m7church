<x-layouts.app :title="__('messages.Add New Discipleship')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('messages.Add New Discipleship') }}</h1>

        <form action="{{ route('discipleships.store') }}" method="POST" class="space-y-6 max-w-2xl">
            @csrf

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="discipulador_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Discipler') }} *</label>
                    @if ($isAdmin)
                        <select name="discipulador_id" id="discipulador_id"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                            required>
                            <option value="">-</option>
                            @foreach ($people as $person)
                                <option value="{{ $person->id }}" @selected(old('discipulador_id') == $person->id)>
                                    {{ $person->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('discipulador_id')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    @else
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            {{ __('messages.You will be registered as the discipler for this discipleship.') }}
                        </p>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">{{ __('messages.Discipleships') }} *</label>
                    <div class="flex gap-6">
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="radio" name="discipulado_type" value="person" class="target-type-radio"
                                data-target="person-target" {{ old('discipulado_type', 'person') === 'person' ? 'checked' : '' }}>
                            {{ __('messages.Person') }}
                        </label>
                        <label class="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                            <input type="radio" name="discipulado_type" value="visitor" class="target-type-radio"
                                data-target="visitor-target" {{ old('discipulado_type') === 'visitor' ? 'checked' : '' }}>
                            {{ __('messages.Visitor') }}
                        </label>
                    </div>
                    @error('discipulado_type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-6" id="person-target">
                    <label for="person_target_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Person') }}</label>
                    <select name="discipulado_id_person" id="person_target_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">-</option>
                        @foreach ($people as $person)
                            <option value="{{ $person->id }}">{{ $person->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-6 hidden" id="visitor-target">
                    <label for="visitor_target_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Visitor') }}</label>
                    <select name="discipulado_id_visitor" id="visitor_target_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">-</option>
                        @foreach ($visitors as $visitor)
                            <option value="{{ $visitor->id }}">{{ $visitor->name }}</option>
                        @endforeach
                    </select>
                </div>
                @error('discipulado_id')
                    <span class="text-red-500 text-sm sm:col-span-6">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="started_at"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Start Date') }} *</label>
                    <input type="date" name="started_at" id="started_at"
                        value="{{ old('started_at', now()->format('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('started_at')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Save') }}
                </button>
                <a href="{{ route('discipleships.index') }}"
                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Cancel') }}
                </a>
            </div>
        </form>
    </div>

    <script>
        (function () {
            const radios = document.querySelectorAll('.target-type-radio');
            const personTarget = document.getElementById('person-target');
            const visitorTarget = document.getElementById('visitor-target');
            const personSelect = document.getElementById('person_target_id');
            const visitorSelect = document.getElementById('visitor_target_id');
            const form = personSelect.closest('form');

            function sync() {
                const checked = document.querySelector('.target-type-radio:checked');
                const isVisitor = checked && checked.value === 'visitor';

                personTarget.classList.toggle('hidden', isVisitor);
                visitorTarget.classList.toggle('hidden', !isVisitor);
                personSelect.disabled = isVisitor;
                visitorSelect.disabled = !isVisitor;
            }

            radios.forEach((radio) => radio.addEventListener('change', sync));
            sync();

            form.addEventListener('submit', function () {
                const checked = document.querySelector('.target-type-radio:checked');
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'discipulado_id';
                hidden.value = checked && checked.value === 'visitor' ? visitorSelect.value : personSelect.value;
                form.appendChild(hidden);
            });
        })();
    </script>
</x-layouts.app>
