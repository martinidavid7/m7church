<x-layouts.app :title="__('messages.Edit') . ' - ' . __('messages.Service')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('messages.Edit') }} - {{ __('messages.Service') }}</h1>

        <form action="{{ route('services.update', ['service' => $service->id]) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="service"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Service') }} *</label>
                    <input type="text" name="service" id="service"
                        value="{{ old('service', $service->service) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('service')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="service_type_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Service Type') }} *</label>
                    <select name="service_type_id" id="service_type_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                        <option value="">Selecione um tipo</option>
                        @foreach ($serviceTypes as $serviceType)
                            <option value="{{ $serviceType->id }}"
                                {{ old('service_type_id', $service->service_type_id) == $serviceType->id ? 'selected' : '' }}>
                                {{ $serviceType->service_type }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_type_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="day_of_week"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Day of Week') }} *</label>
                    <select name="day_of_week" id="day_of_week"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                        <option value="">Selecione um dia</option>
                        @foreach (\App\Models\Service::DAYS_OF_WEEK as $day)
                            <option value="{{ $day }}" {{ old('day_of_week', $service->day_of_week) == $day ? 'selected' : '' }}>
                                {{ $day }}
                            </option>
                        @endforeach
                    </select>
                    @error('day_of_week')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="time"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Time') }} *</label>
                    @php
                        $currentTime = $service->time ? \Illuminate\Support\Carbon::parse($service->time)->format('H:i') : '';
                    @endphp
                    <select name="time" id="time"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                        <option value="">Selecione um horário</option>
                        @foreach (\App\Models\Service::timeOptions() as $timeOption)
                            <option value="{{ $timeOption }}" {{ old('time', $currentTime) == $timeOption ? 'selected' : '' }}>
                                {{ $timeOption }}
                            </option>
                        @endforeach
                    </select>
                    @error('time')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Update') }}
                </button>
                <a href="{{ route('services.index') }}"
                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Cancel') }}
                </a>
            </div>
        </form>
    </div>

</x-layouts.app>
