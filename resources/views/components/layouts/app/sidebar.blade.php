<div class="min-h-screen flex" x-data="{ sidebarOpen: localStorage.getItem('sidebarOpen') === 'false' ? false : true }" x-init="$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', val))">

    <!-- IMPERSONATE BANNER -->
    @if (auth()->check() && auth()->user()->isImpersonated())
        <div
            class="fixed top-0 left-0 right-0 bg-orange-500 text-white px-4 py-2 z-50 flex items-center justify-between shadow-lg">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                </svg>
                <span class="font-semibold">Você está visualizando como: {{ auth()->user()->name }}</span>
            </div>
            <a href="{{ route('impersonate.leave') }}"
                class="bg-white text-orange-600 px-3 py-1 rounded-md hover:bg-orange-50 transition font-semibold text-sm">
                Voltar para minha conta
            </a>
        </div>
    @endif

    <!-- SIDEBAR -->
    <aside :class="sidebarOpen ? 'w-64' : 'w-20'"
        class="bg-white border-r shadow-sm flex flex-col transition-all duration-300 @if (auth()->check() && auth()->user()->isImpersonated()) mt-12 @endif">

        <!-- Logo and Toggle -->
        <div class="px-6 py-6 flex items-center justify-between gap-2 border-b">
            <a href="{{ route('dashboard.index') }}" class="flex items-center gap-2 overflow-hidden" wire:navigate>
                <div
                    class="h-10 w-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold flex-shrink-0">
                    M7
                </div>
                <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
                    <h1 class="text-sm font-semibold whitespace-nowrap">M7 Church</h1>
                    <p class="text-[11px] text-slate-500 -mt-1 whitespace-nowrap">Gestão de membros</p>
                </div>
            </a>
            <button @click="sidebarOpen = !sidebarOpen"
                class="p-1.5 hover:bg-slate-100 rounded-lg transition flex-shrink-0" :class="!sidebarOpen && 'mx-auto'">
                <svg class="w-5 h-5 text-slate-600 transition-transform duration-300"
                    :class="!sidebarOpen && 'rotate-180'" fill="none" stroke="currentColor" stroke-width="2"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
                </svg>
            </button>
        </div>

        <!-- MENU -->
        <nav class="flex-1 px-4 py-6 text-sm space-y-1">

            <p x-show="sidebarOpen" x-transition class="text-[11px] uppercase tracking-wide text-slate-400 px-2 mb-2">
                {{ __('messages.Platform') }}</p>
            @role('Admin|Pastor Presidente|Pastor Auxiliar|Secretaria')
                <div class="relative group">
                    <a href="{{ route('dashboard.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard.index') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                        wire:navigate>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition
                            class="whitespace-nowrap">{{ __('messages.Dashboard') }}</span>
                    </a>
                    <div x-show="!sidebarOpen"
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                        {{ __('messages.Dashboard') }}
                    </div>
                </div>
            @endrole

            <div class="relative group">
                <a href="{{ route('person.my-profile') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                    class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('person.my-profile') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                    wire:navigate>
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                    <span x-show="sidebarOpen" x-transition class="whitespace-nowrap">Meu Perfil</span>
                </a>
                <div x-show="!sidebarOpen"
                    class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                    Meu Perfil
                </div>
            </div>

            @role('Admin|Pastor Presidente')
                <p x-show="sidebarOpen" x-transition
                    class="text-[11px] uppercase tracking-wide text-slate-400 px-2 mt-6 mb-2">{{ __('messages.Church') }}
                </p>
                <div class="relative group">
                    <a href="{{ route('church.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('church.index') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                        wire:navigate>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 21V10l4-7 4 7v11M3 21h18M8 21h8" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition
                            class="whitespace-nowrap">{{ __('messages.Church') }}</span>
                    </a>
                    <div x-show="!sidebarOpen"
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                        {{ __('messages.Church') }}
                    </div>
                </div>
            @endrole

            @php $myMinistries = auth()->user()->ministries(); @endphp
            @if ($myMinistries->isNotEmpty())
                <p x-show="sidebarOpen" x-transition
                    class="text-[11px] uppercase tracking-wide text-slate-400 px-2 mt-6 mb-2">Meus Ministérios</p>

                @foreach ($myMinistries as $myMinistry)
                    <div class="relative group">
                        <a href="{{ route('ministries.show', $myMinistry) }}"
                            :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                            class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition overflow-hidden {{ request()->routeIs('ministries.show') && request()->route('ministry')?->id === $myMinistry->id ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                            wire:navigate>
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z" />
                            </svg>
                            <span x-show="sidebarOpen" x-transition class="whitespace-nowrap overflow-hidden text-ellipsis">
                                {{ $myMinistry->name }}
                                @if ($myMinistry->pivot->role === 'lider')
                                    <span class="text-[10px] text-indigo-500">(líder)</span>
                                @endif
                            </span>
                        </a>
                        <div x-show="!sidebarOpen"
                            class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                            {{ $myMinistry->name }}
                        </div>
                    </div>
                @endforeach
            @endif

            @role('Admin|Pastor Presidente|Pastor Auxiliar|Secretaria')
                <p x-show="sidebarOpen" x-transition
                    class="text-[11px] uppercase tracking-wide text-slate-400 px-2 mt-6 mb-2">{{ __('messages.People') }}
                </p>

                <div class="relative group">
                    <a href="{{ route('person.index', ['active' => 1]) }}"
                        :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('person.index') && request()->query('active', '1') == '1' ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                        wire:navigate>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition
                            class="whitespace-nowrap">{{ __('messages.Members') }}</span>
                    </a>
                    <div x-show="!sidebarOpen"
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                        {{ __('messages.Members') }}
                    </div>
                </div>

                <div class="relative group">
                    <a href="{{ route('visitors.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('visitors.index') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                        wire:navigate>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M22 10.5h-6m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.374 21c-2.331 0-4.512-.645-6.374-1.766z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition
                            class="whitespace-nowrap">{{ __('messages.Visitors') }}</span>
                    </a>
                    <div x-show="!sidebarOpen"
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                        {{ __('messages.Visitors') }}
                    </div>
                </div>
            @endrole

            @role('Admin')
                <p x-show="sidebarOpen" x-transition
                    class="text-[11px] uppercase tracking-wide text-slate-400 px-2 mt-6 mb-2">
                    {{ __('messages.Registration') }}</p>
                <div class="relative group">
                    <a href="{{ route('cities.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('cities.index') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                        wire:navigate>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition
                            class="whitespace-nowrap">{{ __('messages.Cities') }}</span>
                    </a>
                    <div x-show="!sidebarOpen"
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                        {{ __('messages.Cities') }}
                    </div>
                    <a href="{{ route('ministries.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('ministries.index') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                        wire:navigate>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition
                            class="whitespace-nowrap">{{ __('messages.Ministries') }}</span>
                    </a>
                    <div x-show="!sidebarOpen"
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                        {{ __('messages.Ministries') }}
                    </div>

                    <a href="{{ route('service_type.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('service_type.*') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                        wire:navigate>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition
                            class="whitespace-nowrap">{{ __('messages.Service Type') }}</span>
                    </a>
                    <div x-show="!sidebarOpen"
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                        {{ __('messages.Service Type') }}
                    </div>

                    <a href="{{ route('services.index') }}" :class="sidebarOpen ? 'justify-start' : 'justify-center'"
                        class="flex items-center gap-2 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('services.*') ? 'bg-indigo-50 text-indigo-600' : 'hover:bg-indigo-50 hover:text-indigo-600' }}"
                        wire:navigate>
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition
                            class="whitespace-nowrap">{{ __('messages.Services') }}</span>
                    </a>
                    <div x-show="!sidebarOpen"
                        class="absolute left-full ml-2 px-2 py-1 bg-slate-900 text-white text-xs rounded hidden group-hover:block whitespace-nowrap z-50 top-1/2 -translate-y-1/2">
                        {{ __('messages.Services') }}
                    </div>

                </div>
            @endrole
        </nav>

        <!-- USER -->
        <div class="p-4 border-t" x-data="{ userMenuOpen: false }">
            <div class="relative">
                <button @click="userMenuOpen = !userMenuOpen"
                    :class="sidebarOpen ? 'justify-between' : 'justify-center'"
                    class="flex items-center gap-3 w-full hover:bg-slate-50 rounded-lg p-2 transition">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div
                            class="h-10 w-10 rounded-xl bg-slate-200 flex items-center justify-center font-semibold text-sm flex-shrink-0">
                            {{ auth()->user()->initials() }}
                        </div>
                        <div x-show="sidebarOpen" x-transition class="flex-1 text-left">
                            <p class="text-sm font-medium whitespace-nowrap overflow-hidden text-ellipsis">
                                {{ auth()->user()->name }}</p>
                            <p class="text-[11px] text-slate-500 whitespace-nowrap overflow-hidden text-ellipsis">
                                {{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <svg x-show="sidebarOpen" x-transition class="w-4 h-4 text-slate-400 flex-shrink-0"
                        :class="userMenuOpen && 'rotate-180'" fill="none" stroke="currentColor"
                        stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <!-- User Menu Dropdown -->
                <div x-show="userMenuOpen" x-transition @click.away="userMenuOpen = false"
                    class="absolute bottom-full left-0 right-0 mb-2 bg-white border shadow-lg rounded-lg overflow-hidden">
                    <a href="{{ route('person.my-profile') }}"
                        class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-sm" wire:navigate>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition>Meu Perfil</span>
                    </a>
                    <a href="{{ route('settings.profile') }}"
                        class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-sm" wire:navigate>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.384.764.78.93.398.164.855.142 1.205-.108l.737-.527a1.125 1.125 0 011.45.12l.773.774c.39.389.44 1.002.12 1.45l-.527.737c-.25.35-.272.806-.107 1.204.165.397.505.71.93.78l.893.15c.543.09.94.56.94 1.109v1.094c0 .55-.397 1.02-.94 1.11l-.893.149c-.425.07-.765.383-.93.78-.165.398-.143.854.107 1.204l.527.738c.32.447.269 1.06-.12 1.45l-.774.773a1.125 1.125 0 01-1.449.12l-.738-.527c-.35-.25-.806-.272-1.203-.107-.397.165-.71.505-.781.929l-.149.894c-.09.542-.56.94-1.11.94h-1.094c-.55 0-1.019-.398-1.11-.94l-.148-.894c-.071-.424-.384-.764-.781-.93-.398-.164-.854-.142-1.204.108l-.738.527c-.447.32-1.06.269-1.45-.12l-.773-.774a1.125 1.125 0 01-.12-1.45l.527-.737c.25-.35.273-.806.108-1.204-.165-.397-.505-.71-.93-.78l-.894-.15c-.542-.09-.94-.56-.94-1.109v-1.094c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.765-.383.93-.78.165-.398.143-.854-.107-1.204l-.527-.738a1.125 1.125 0 01.12-1.45l.773-.773a1.125 1.125 0 011.45-.12l.737.527c.35.25.807.272 1.204.107.397-.165.71-.505.78-.929l.15-.894z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span x-show="sidebarOpen" x-transition>{{ __('Settings') }}</span>
                    </a>
                    <div class="border-t"></div>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 px-4 py-2.5 hover:bg-slate-50 text-sm w-full text-left text-red-600"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                            <span x-show="sidebarOpen" x-transition>{{ __('Log Out') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </aside>

    <!-- MAIN CONTENT -->
    <main class="flex-1 overflow-auto @if (auth()->check() && auth()->user()->isImpersonated()) mt-12 @endif">
        {{ $slot }}
    </main>

</div>
