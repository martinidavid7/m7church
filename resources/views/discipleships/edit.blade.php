<x-layouts.app :title="__('messages.Edit') . ' - ' . __('messages.Discipleships')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('messages.Edit') }} - {{ __('messages.Discipleships') }}</h1>

        <form action="{{ route('discipleships.update', $discipleship) }}" method="POST" class="space-y-6 max-w-2xl">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Discipler') }}</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $discipleship->discipulador?->name }}</p>
                </div>
                <div class="sm:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Discipleships') }}</label>
                    <p class="mt-1 text-sm text-gray-900 dark:text-white">
                        {{ $discipleship->discipulado?->name }}
                        <span class="text-gray-400">({{ $discipleship->discipulado_type === 'visitor' ? __('messages.Visitor') : __('messages.Person') }})</span>
                    </p>
                </div>
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                Para trocar o discipulador ou o alvo, registre um novo discipulado — o atual será encerrado automaticamente.
                <a href="{{ route('discipleships.show', $discipleship) }}" class="text-indigo-600 hover:underline">{{ __('messages.View Details') }}</a>
            </p>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="started_at"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Start Date') }} *</label>
                    <input type="date" name="started_at" id="started_at"
                        value="{{ old('started_at', $discipleship->started_at?->format('Y-m-d')) }}"
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
                    {{ __('messages.Update') }}
                </button>
                <a href="{{ route('discipleships.index') }}"
                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Cancel') }}
                </a>
            </div>
        </form>

        <form action="{{ route('discipleships.end', $discipleship) }}" method="POST" class="mt-3"
            onsubmit="return confirm('{{ __('messages.End Discipleship') }}?');">
            @csrf
            @method('PATCH')
            <button type="submit"
                class="inline-flex justify-center rounded-md border border-transparent bg-red-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                {{ __('messages.End Discipleship') }}
            </button>
        </form>
    </div>
</x-layouts.app>
