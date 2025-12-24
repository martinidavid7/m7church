<x-layouts.app :title="__('Edit')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('Church') }} {{ __('Edit') }}</h1>

        <form action="{{ route('church.update', ['id' => $church->id]) }}" method="POST" enctype="multipart/form-data" x-data class="space-y-6">
            @csrf {{-- Token CSRF para segurança --}}
            @method('PUT')

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="church_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome da Igreja *</label>
                    <input type="text" name="church_name" id="church_name" autocomplete="organization" value="{{ $church->church_name ?? old('church_name') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('church_name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="logo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Logo da Igreja</label>
                    @if($church->logo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $church->logo) }}" alt="Logo atual" class="w-20 h-20 object-contain">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Logo atual</p>
                        </div>
                    @endif
                    <input type="file" name="logo" id="logo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('logo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>


            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Endereço</label>
                    <input type="text" name="address" id="address" autocomplete="Address" value="{{ $church->address }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>

                <div class="sm:col-span-2">
                    <label for="number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número</label>
                    <input type="text" name="number" id="number" autocomplete="Number" value="{{ $church->number }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="neighborhood" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bairro</label>
                    <input type="text" name="neighborhood" id="neighborhood" autocomplete="neighborhood" value="{{ $church->neighborhood }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>

                <div class="sm:col-span-3">
                    <label for="complement" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Complemento</label>
                    <input type="text" name="complement" id="number" autocomplete="complement" value="{{  $church->complement }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CEP</label>
                    <input type="text" name="zip_code" id="zip_code" autocomplete="zip_code" value="{{ $church->zip_code }}" x-mask="99999-999"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>

                <div class="sm:col-span-1">
                    <label for="uf_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UF</label>
                    <select name="uf_id" id="uf_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione a UF</option>
                        @foreach ($uf as $state)
                            <option value="{{ $state->id }}"
                                {{ $church->city && $church->city->uf_id == $state->id ? 'selected' : '' }}>
                                {{ $state->uf }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="sm:col-span-3">
                    <label for="city_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cidade</label>
                    <select name="city_id" id="city_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione a Cidade</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ $church->city_id == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="church_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone da Igreja</label>
                    <input type="text" name="church_phone" id="church_phone" autocomplete="Church Phone" value="{{ $church->church_phone }}" x-mask="(99) 99999-9999"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>
                <div class="sm:col-span-3">
                    <label for="church_mail" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email da Igreja</label>
                    <input type="email" name="church_mail" id="church_mail" autocomplete="email" value="{{ $church->church_mail }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="church_type_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo de Igreja *</label>
                    <select name="church_type_id" id="church_type_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                        <option value="">Selecione o Tipo</option>
                        @foreach ($churchTypes as $type)
                            <option value="{{ $type->id }}" {{ ($church->church_type_id ?? old('church_type_id')) == $type->id ? 'selected' : '' }}>
                                {{ $type->church_type }}
                            </option>
                        @endforeach
                    </select>
                    @error('church_type_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="parent_church_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Igreja Pai</label>
                    <select name="parent_church_id" id="parent_church_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Nenhuma (Matriz)</option>
                        @foreach ($churches as $parentChurch)
                            @if($parentChurch->id !== $church->id)
                                <option value="{{ $parentChurch->id }}" {{ ($church->parent_church_id ?? old('parent_church_id')) == $parentChurch->id ? 'selected' : '' }}>
                                    {{ $parentChurch->church_name }}
                                </option>
                            @endif
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
                        <option value="{{ $person->id }}" {{ ($church->pastor_id ?? old('pastor_id')) == $person->id ? 'selected' : '' }}>
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
                    Salvar
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ufSelect = document.getElementById('uf_id');
            const citySelect = document.getElementById('city_id');
            const currentCityId = {{ $church->city_id ?? 'null' }};

            ufSelect.addEventListener('change', function() {
                const ufId = this.value;

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
                                if (city.id === currentCityId) {
                                    option.selected = true;
                                }
                                citySelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Erro ao carregar cidades:', error);
                        });
                }
            });
        });
    </script>
</x-layouts.app>