<x-layouts.app :title="__('messages.Registration')">
    <div class="p-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">{{ __('messages.Registration') }} - {{ __('messages.Visitors') }}</h1>

        <form action="store" method="POST" enctype="multipart/form-data" x-data class="space-y-6">
            @csrf {{-- Token CSRF para segurança --}}

            {{-- Campo oculto para user_id (preenchido pelo controller) --}}
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">

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

                <div class="sm:col-span-1">
                    <label for="visit_date"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data da Visita *</label>
                    <input type="date" name="visit_date" id="visit_date"
                        value="{{ old('visit_date', date('Y-m-d')) }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2"
                        required>
                    @error('visit_date')
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
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
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

                <div class="sm:col-span-3">
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
                </div>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="profession"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profissão</label>
                    <input type="text" name="profession" id="profession" value="{{ old('profession') }}"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
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
                </div>

                <div class="sm:col-span-2">
                    <label for="number"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Number') }}</label>
                    <input type="text" name="number" id="number" value="{{ old('number') }}"
                        autocomplete="Number"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="neighborhood"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Neighborhood') }}</label>
                    <input type="text" name="neighborhood" id="neighborhood" value="{{ old('neighborhood') }}"
                        autocomplete="neighborhood"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>

                <div class="sm:col-span-3">
                    <label for="complement"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Complement') }}</label>
                    <input type="text" name="complement" id="complement" value="{{ old('complement') }}"
                        autocomplete="complement"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                </div>
            </div>
            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <label for="zip_code"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Zip Code') }}</label>
                    <input type="text" name="zip_code" id="zip_code" value="{{ old('zip_code') }}"
                        autocomplete="zip_code" x-mask="99999-999"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
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
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('messages.Email') }}</label>
                    <input type="email" name="mail" id="mail" value="{{ old('mail') }}"
                        autocomplete="email"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">
                    @error('mail')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div class="sm:col-span-6">
                    <div class="flex items-center">
                        <input id="accept_receive_messages" name="accept_receive_messages" type="checkbox" value="1" {{ old('accept_receive_messages') ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:ring-offset-gray-800">
                        <label for="accept_receive_messages" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">Aceita receber mensagens</label>
                    </div>
                </div>
            </div>

            {{-- Observações --}}
            <div class="border-b border-gray-200 dark:border-gray-700 pb-4 pt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Observações</h2>
            </div>

            <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
                <div class="sm:col-span-6">
                    <label for="observations"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                    <textarea name="observations" id="observations" rows="3"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white sm:text-sm py-2">{{ old('observations') }}</textarea>
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

</x-layouts.app>
