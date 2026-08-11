<x-layouts.app :title="__('messages.Registration') . ' - ' . __('messages.Ministry')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('messages.Registration') }} - {{ __('messages.Ministry') }}</h1>

        <form action="{{ route('ministries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            {{-- Informações Básicas --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informações do Ministério</h2>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome do Ministério *</label>
                    <input type="text" name="name" id="name" autocomplete="name"
                        value="{{ old('name') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="leader_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Líder do Ministério</label>
                    <select name="leader_id" id="leader_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione um líder</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ old('leader_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('leader_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="description"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
                    <textarea name="description" id="description" rows="4"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">{{ old('description') }}</textarea>
                    @error('description')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="logo"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo do Ministério</label>
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300
                               file:mr-4 file:py-2 file:px-4
                               file:rounded-md file:border-0
                               file:text-sm file:font-semibold
                               file:bg-indigo-50 file:text-indigo-700
                               hover:file:bg-indigo-100
                               dark:file:bg-gray-700 dark:file:text-gray-300
                               dark:hover:file:bg-gray-600">
                    @error('logo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">PNG, JPG ou JPEG (MAX. 2MB)</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Save') }}
                </button>
                <a href="{{ route('ministries.index') }}"
                    class="inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 py-2 px-4 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Cancel') }}
                </a>
            </div>
        </form>
    </div>

</x-layouts.app>
