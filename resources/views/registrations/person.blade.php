<x-layouts.app :title="__('Edit')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('Person') }} {{ __('Edit') }}
        </h1>

        <form action="{{ route('person.update', ['id' => $person->id]) }}" method="POST" enctype="multipart/form-data" x-data class="space-y-6">
            @csrf {{-- Token CSRF para segurança --}}
            @method('PUT')

            {{-- Informações Pessoais --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Pessoais</h2>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Completo *</label>
                    <input type="text" name="name" id="name" autocomplete="name"
                        value="{{ $person->name ?? old('name') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('name')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="birth_date"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Nascimento</label>
                    <input type="date" name="birth_date" id="birth_date"
                        value="{{ $person->birth_date ? $person->birth_date->format('Y-m-d') : old('birth_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('birth_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-1">
                    <label for="active"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                    <select id="active" name="active"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option {{ $person->active == 0 ? 'selected' : '' }} value="0">Inativo</option>
                        <option {{ $person->active == 1 ? 'selected' : '' }} value="1">Ativo</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="gender"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sexo</label>
                    <select id="gender" name="gender"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione</option>
                        <option value="M" {{ ($person->gender ?? old('gender')) == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ ($person->gender ?? old('gender')) == 'F' ? 'selected' : '' }}>Feminino</option>
                    </select>
                    @error('gender')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="marital_status"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado Civil</label>
                    <select id="marital_status" name="marital_status"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione</option>
                        <option value="Solteiro(a)" {{ ($person->marital_status ?? old('marital_status')) == 'Solteiro(a)' ? 'selected' : '' }}>Solteiro(a)</option>
                        <option value="Casado(a)" {{ ($person->marital_status ?? old('marital_status')) == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                        <option value="Divorciado(a)" {{ ($person->marital_status ?? old('marital_status')) == 'Divorciado(a)' ? 'selected' : '' }}>Divorciado(a)</option>
                        <option value="Viúvo(a)" {{ ($person->marital_status ?? old('marital_status')) == 'Viúvo(a)' ? 'selected' : '' }}>Viúvo(a)</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label for="photo"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto</label>
                    @if($person->photo)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $person->photo) }}" alt="Foto atual" class="w-20 h-20 object-cover rounded-full">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Foto atual</p>
                        </div>
                    @endif
                    <input type="file" name="photo" id="photo" accept="image/*"
                        class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                    @error('photo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="profession"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profissão</label>
                    <input type="text" name="profession" id="profession" value="{{ $person->profession ?? old('profession') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>

                <div class="sm:col-span-3">
                    <label for="education_level"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Escolaridade</label>
                    <select id="education_level" name="education_level"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione</option>
                        <option value="Fundamental Incompleto" {{ ($person->education_level ?? old('education_level')) == 'Fundamental Incompleto' ? 'selected' : '' }}>Fundamental Incompleto</option>
                        <option value="Fundamental Completo" {{ ($person->education_level ?? old('education_level')) == 'Fundamental Completo' ? 'selected' : '' }}>Fundamental Completo</option>
                        <option value="Médio Incompleto" {{ ($person->education_level ?? old('education_level')) == 'Médio Incompleto' ? 'selected' : '' }}>Médio Incompleto</option>
                        <option value="Médio Completo" {{ ($person->education_level ?? old('education_level')) == 'Médio Completo' ? 'selected' : '' }}>Médio Completo</option>
                        <option value="Superior Incompleto" {{ ($person->education_level ?? old('education_level')) == 'Superior Incompleto' ? 'selected' : '' }}>Superior Incompleto</option>
                        <option value="Superior Completo" {{ ($person->education_level ?? old('education_level')) == 'Superior Completo' ? 'selected' : '' }}>Superior Completo</option>
                        <option value="Pós-graduação" {{ ($person->education_level ?? old('education_level')) == 'Pós-graduação' ? 'selected' : '' }}>Pós-graduação</option>
                    </select>
                </div>
            </div>

            {{-- Endereço --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Endereço</h2>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="address"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Endereço</label>
                    <input type="text" name="address" id="address" autocomplete="Address"
                        value="{{ $person->address ?? old('address') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>

                <div class="sm:col-span-2">
                    <label for="number"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número</label>
                    <input type="text" name="number" id="number" autocomplete="Number"
                        value="{{ $person->number ?? old('number') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="neighborhood"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Bairro</label>
                    <input type="text" name="neighborhood" id="neighborhood" autocomplete="neighborhood"
                        value="{{ $person->neighborhood ?? old('neighborhood') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>

                <div class="sm:col-span-3">
                    <label for="complement"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Complemento</label>
                    <input type="text" name="complement" id="complement" autocomplete="complement"
                        value="{{ $person->complement ?? old('complement') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">CEP</label>
                    <input type="text" name="zip_code" id="zip_code" autocomplete="zip_code"
                        value="{{ $person->zip_code ?? old('zip_code') }}" x-mask="99999-999"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>

                <div class="sm:col-span-1">
                    <label for="uf_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UF</label>
                    <select name="uf_id" id="uf_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione a UF</option>
                        @foreach ($uf as $state)
                            <option value="{{ $state->id }}"
                                {{ ($person->city->uf_id ?? old('uf_id')) == $state->id ? 'selected' : '' }}>
                                {{ $state->uf }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="sm:col-span-3">
                    <label for="city_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.City') }}</label>
                    <select name="city_id" id="city_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione a Cidade</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ ($person->city_id ?? old('city_id')) == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Contato --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Contato</h2>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="mobile_phone"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone Celular *</label>
                    <input type="text" name="mobile_phone" id="mobile_phone"
                        value="{{ $person->mobile_phone ?? old('mobile_phone') }}"
                        autocomplete="tel" x-mask="(99) 99999-9999"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('mobile_phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="landline_phone"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telefone Fixo</label>
                    <input type="text" name="landline_phone" id="landline_phone"
                        value="{{ $person->landline_phone ?? old('landline_phone') }}"
                        autocomplete="tel" x-mask="(99) 9999-9999"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('landline_phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="mail"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email *</label>
                    <input type="email" name="mail" id="mail"
                        value="{{ $person->mail ?? old('mail') }}"
                        autocomplete="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('mail')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Informações Eclesiásticas --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Eclesiásticas</h2>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="church_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Igreja</label>
                    <select name="church_id" id="church_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione uma Igreja</option>
                        @foreach ($churches as $church)
                            <option value="{{ $church->id }}" {{ ($person->church_id ?? old('church_id')) == $church->id ? 'selected' : '' }}>
                                {{ $church->church_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('church_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="baptism_date"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Batismo</label>
                    <input type="date" name="baptism_date" id="baptism_date"
                        value="{{ $person->baptism_date ? $person->baptism_date->format('Y-m-d') : old('baptism_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('baptism_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="membership_date"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Membresia</label>
                    <input type="date" name="membership_date" id="membership_date"
                        value="{{ $person->membership_date ? $person->membership_date->format('Y-m-d') : old('membership_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>

                <div class="sm:col-span-6">
                    <label for="observations"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                    <textarea name="observations" id="observations" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">{{ $person->observations ?? old('observations') }}</textarea>
                </div>
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
            const currentCityId = {{ $person->city_id ?? 'null' }};

            // Função para carregar cidades por UF
            function loadCitiesByUf(ufId, selectedCityId = null) {
                // Limpar o select de cidades
                citySelect.innerHTML = '<option value="">Selecione a Cidade</option>';

                if (ufId) {
                    // Fazer requisição para buscar as cidades da UF selecionada
                    fetch(`/person/cities-by-uf/${ufId}`)
                        .then(response => response.json())
                        .then(cities => {
                            cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                // Marcar a cidade atual como selecionada
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

            // Carregar cidades da UF atual ao carregar a página
            const currentUfId = ufSelect.value;
            if (currentUfId) {
                loadCitiesByUf(currentUfId, currentCityId);
            }

            // Event listener para mudanças na UF
            ufSelect.addEventListener('change', function() {
                const ufId = this.value;
                loadCitiesByUf(ufId);
            });
        });
    </script>
</x-layouts.app>
