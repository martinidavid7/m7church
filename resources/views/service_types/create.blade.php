<x-layouts.app :title="__('messages.Registration') . ' - ' . __('messages.Service Type')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('messages.Registration') }} - {{ __('messages.Service Type') }}</h1>

        <form action="{{ route('service_type.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="service_type"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Service Type') }} *</label>
                    <input type="text" name="service_type" id="service_type"
                        value="{{ old('service_type') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('service_type')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Save') }}
                </button>
                <a href="{{ route('service_type.index') }}"
                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Cancel') }}
                </a>
            </div>
        </form>
    </div>

</x-layouts.app>
