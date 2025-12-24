<x-layouts.app :title="__('messages.Registration')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('messages.Register') }} {{ __('messages.Church') }}</h1>

        @if(session('error'))
            <div class="mb-4 rounded-md bg-red-50 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('church.store') }}" method="POST" enctype="multipart/form-data" x-data class="space-y-6">
            @csrf {{-- Token CSRF para segurança --}}

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="church_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Church Name')}} *</label>
                    <input type="text" name="church_name" id="church_name" autocomplete="organization" value="{{ old('church_name') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('church_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo da Igreja</label>
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('logo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>


            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Address')}}</label>
                    <input type="text" name="address" id="address" autocomplete="Address" value="{{ old('address') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Number')}}</label>
                    <input type="text" name="number" id="number" autocomplete="Number" value="{{ old('number') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="neighborhood" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Neighborhood')}}</label>
                    <input type="text" name="neighborhood" id="neighborhood" autocomplete="neighborhood" value="{{ old('neighborhood') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('neighborhood')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="complement" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Complement')}}</label>
                    <input type="text" name="complement" id="complement" autocomplete="complement" value="{{ old('complement') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('complement')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Zip Code')}}</label>
                    <input type="text" name="zip_code" id="zip_code" autocomplete="zip_code" x-mask="99999-999" value="{{ old('zip_code') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('zip_code')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-1">
                    <label for="uf_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.State')}}</label>
                    <select name="uf_id" id="uf_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione a UF</option>
                        @foreach ($uf as $state)
                            <option value="{{ $state->id }}" {{ old('uf_id') == $state->id ? 'selected' : '' }}>{{ $state->uf }}</option>
                        @endforeach
                    </select>
                    @error('uf_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-3">
                    <label for="city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.City')}}</label>
                    <select name="city_id" id="city_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione a Cidade</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="church_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Church Phone')}}</label>
                    <input type="text" name="church_phone" id="church_phone" autocomplete="Church Phone" x-mask="(99) 99999-9999" value="{{ old('church_phone') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('church_phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-3">
                    <label for="church_mail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Church Mail')}}</label>
                    <input type="email" name="church_mail" id="church_mail" autocomplete="email" value="{{ old('church_mail') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('church_mail')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="church_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Church Type')}} *</label>
                    <select name="church_type_id" id="church_type_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                        <option value="">Selecione o Tipo</option>
                        @foreach ($churchTypes as $type)
                            <option value="{{ $type->id }}" {{ old('church_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->church_type }}
                            </option>
                        @endforeach
                    </select>
                    @error('church_type_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="parent_church_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Parent Church')}}</label>
                    <select name="parent_church_id" id="parent_church_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Nenhuma (Matriz)</option>
                        @foreach ($churches as $parentChurch)
                            <option value="{{ $parentChurch->id }}" {{ old('parent_church_id') == $parentChurch->id ? 'selected' : '' }}>
                                {{ $parentChurch->church_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('parent_church_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <label for="pastor_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Pastor</label>
                <select name="pastor_id" id="pastor_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    <option value="">Selecione um Pastor</option>
                    @foreach ($persons as $person)
                        <option value="{{ $person->id }}" {{ old('pastor_id') == $person->id ? 'selected' : '' }}>
                            {{ $person->name }}
                        </option>
                    @endforeach
                </select>
                @error('pastor_id')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div>
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    Cadastrar Igreja
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ufSelect = document.getElementById('uf_id');
            const citySelect = document.getElementById('city_id');
            const oldCityId = "{{ old('city_id') }}";

            function loadCities(ufId, selectedCityId = null) {
                // Limpar o select de cidades
                citySelect.innerHTML = '<option value="">Selecione a Cidade</option>';

                if (ufId) {
                    // Fazer requisição para buscar as cidades da UF selecionada
                    fetch(`/church/cities-by-uf/${ufId}`)
                        .then(response => response.json())
                        .then(cities => {
                            cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                // Selecionar a cidade antiga se houver
                                if (selectedCityId && city.id == selectedCityId) {
                                    option.selected = true;
                                }
                                citySelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Erro ao carregar cidades:', error);
                        });
                }
            }

            // Carregar cidades ao mudar UF
            ufSelect.addEventListener('change', function() {
                loadCities(this.value);
            });

            // Carregar cidades se houver UF selecionada (ao recarregar com erros de validação)
            if (ufSelect.value && oldCityId) {
                loadCities(ufSelect.value, oldCityId);
            }
        });
    </script>
</x-layouts.app>