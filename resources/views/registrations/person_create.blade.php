<x-layouts.app :title="__('messages.Registration')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('messages.Registration') }}
            {{ __('messages.Members') }}</h1>

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

        <form action="{{ route('person.store') }}" method="POST" enctype="multipart/form-data" x-data class="space-y-6">
            @csrf {{-- Token CSRF para segurança --}}

            {{-- Informações Pessoais --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Informações Pessoais</h2>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="name"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome Completo *</label>
                    <input type="text" name="name" id="name" autocomplete="name"
                        value="{{ old('name') }}"
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
                        value="{{ old('birth_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('birth_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-1">
                    <label for="active"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Status') }}</label>
                    <select id="active" name="active"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="0" {{ old('active') == '0' ? 'selected' : '' }}>
                            {{ __('messages.Inactive') }}</option>
                        <option value="1" {{ old('active', '1') == '1' ? 'selected' : '' }}>
                            {{ __('messages.Active') }}</option>
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
                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Feminino</option>
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
                        <option value="Solteiro(a)" {{ old('marital_status') == 'Solteiro(a)' ? 'selected' : '' }}>Solteiro(a)</option>
                        <option value="Casado(a)" {{ old('marital_status') == 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                        <option value="Divorciado(a)" {{ old('marital_status') == 'Divorciado(a)' ? 'selected' : '' }}>Divorciado(a)</option>
                        <option value="Viúvo(a)" {{ old('marital_status') == 'Viúvo(a)' ? 'selected' : '' }}>Viúvo(a)</option>
                    </select>
                    @error('marital_status')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="photo"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Foto</label>
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
                    <input type="text" name="profession" id="profession" value="{{ old('profession') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('profession')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="education_level"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Escolaridade</label>
                    <select id="education_level" name="education_level"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione</option>
                        <option value="Fundamental Incompleto" {{ old('education_level') == 'Fundamental Incompleto' ? 'selected' : '' }}>Fundamental Incompleto</option>
                        <option value="Fundamental Completo" {{ old('education_level') == 'Fundamental Completo' ? 'selected' : '' }}>Fundamental Completo</option>
                        <option value="Médio Incompleto" {{ old('education_level') == 'Médio Incompleto' ? 'selected' : '' }}>Médio Incompleto</option>
                        <option value="Médio Completo" {{ old('education_level') == 'Médio Completo' ? 'selected' : '' }}>Médio Completo</option>
                        <option value="Superior Incompleto" {{ old('education_level') == 'Superior Incompleto' ? 'selected' : '' }}>Superior Incompleto</option>
                        <option value="Superior Completo" {{ old('education_level') == 'Superior Completo' ? 'selected' : '' }}>Superior Completo</option>
                        <option value="Pós-graduação" {{ old('education_level') == 'Pós-graduação' ? 'selected' : '' }}>Pós-graduação</option>
                    </select>
                    @error('education_level')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Endereço --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Endereço</h2>
            </div>


            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-4">
                    <label for="address"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Address') }}</label>
                    <input type="text" name="address" id="address" value="{{ old('address') }}"
                        autocomplete="Address"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('address')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="number"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Number') }}</label>
                    <input type="text" name="number" id="number" value="{{ old('number') }}"
                        autocomplete="Number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('number')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="neighborhood"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Neighborhood') }}</label>
                    <input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}"
                        autocomplete="neighborhood"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('neighborhood')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-3">
                    <label for="complement"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Complement') }}</label>
                    <input type="text" name="complement" id="complement" value="{{ old('complement') }}"
                        autocomplete="complement"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('complement')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-2">
                    <label for="zip_code"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Zip Code') }}</label>
                    <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}"
                        autocomplete="zip_code" x-mask="99999-999"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('zip_code')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-1">
                    <label for="uf_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.State') }}</label>
                    <select name="uf_id" id="uf_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione a UF</option>
                        @foreach ($uf as $state)
                            <option value="{{ $state->id }}"
                                {{ old('uf_id') == $state->id ? 'selected' : '' }}>
                                {{ $state->uf }}</option>
                        @endforeach
                    </select>
                    @error('uf_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-3">
                    <label for="city_id"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.City') }}</label>
                    <select name="city_id" id="city_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione a Cidade</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}"
                                {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}</option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
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
                    <input type="text" name="mobile_phone" id="mobile_phone" value="{{ old('mobile_phone') }}"
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
                    <input type="text" name="landline_phone" id="landline_phone" value="{{ old('landline_phone') }}"
                        autocomplete="tel" x-mask="(99) 9999-9999"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('landline_phone')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-2">
                    <label for="mail"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Email') }} *</label>
                    <input type="email" name="mail" id="mail" value="{{ old('mail') }}"
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
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{__('messages.Church')}}</label>
                    <select name="church_id" id="church_id"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                        <option value="">Selecione uma Igreja</option>
                        @foreach ($churches as $church)
                            <option value="{{ $church->id }}" {{ old('church_id') == $church->id ? 'selected' : '' }}>
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
                    <input type="date" name="baptism_date" id="baptism_date" value="{{ old('baptism_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('baptism_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-2">
                    <label for="membership_date"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Membresia</label>
                    <input type="date" name="membership_date" id="membership_date" value="{{ old('membership_date') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('membership_date')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-6">
                    <label for="observations"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                    <textarea name="observations" id="observations" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">{{ old('observations') }}</textarea>
                    @error('observations')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            {{-- Acesso ao Sistema --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Acesso ao Sistema</h2>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Password') }} * (mínimo 6 caracteres)</label>
                    <input type="password" name="password" id="password" autocomplete="new-password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required minlength="6">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="sm:col-span-3">
                    <label for="confirm_password"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Confirm Password') }} *</label>
                    <input type="password" name="confirm_password" id="confirm_password" autocomplete="new-password"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required minlength="6">
                    @error('confirm_password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6 mt-6">
                <div class="sm:col-span-6">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Funções/Cargos</label>
                    <div class="mt-2 space-y-2">
                        @foreach ($roles as $role)
                            <div class="flex items-center">
                                <input type="checkbox" name="roles[]" id="role_{{ $role->id }}" value="{{ $role->name }}"
                                    {{ in_array($role->name, old('roles', [])) ? 'checked' : '' }}
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                <label for="role_{{ $role->id }}" class="ml-3 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $role->name }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                    @error('roles')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div>
                <button type="submit"
                    class="inline-flex justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    {{ __('messages.Save') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ufSelect = document.getElementById('uf_id');
            const citySelect = document.querySelector('select[name="city_id"]');

            ufSelect.addEventListener('change', function() {
                const ufId = this.value;

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
