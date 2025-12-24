<div class="mb-6 bg-white dark:bg-gray-800 shadow-md rounded-lg p-4">
    <form action="{{ route('person.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4 items-end">
        <div class="md:col-span-1">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Name') }}</label>
            <input type="text" name="name" id="name" value="{{ request('name') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div class="md:col-span-1">
            <label for="church_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Church') }}</label>
            <select id="church_id" name="church_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="">Todas</option>
                @foreach($churches as $church)
                    <option value="{{ $church->id }}" {{ request('church_id') == $church->id ? 'selected' : '' }}>
                        {{ $church->church_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="md:col-span-1">
            <label for="active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
            <select id="active" name="active" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                <option value="">Todos</option>
                <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Ativos</option>
                <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inativos</option>
            </select>
        </div>

        <div class="md:col-span-1 lg:col-span-2 flex items-center space-x-2">
            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" /></svg>
                Filtrar
            </button>
            <a href="{{ route('person.index') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-500 text-sm font-medium rounded-md text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                Limpar
            </a>
        </div>
    </form>
</div>
