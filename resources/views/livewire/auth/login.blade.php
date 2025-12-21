<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password, 'active' => 1], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);


        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirect(route('dashboard.index'));
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="max-w-md mx-auto px-6 py-16">

    <div class="text-center mb-10">
        <div class="h-12 w-12 bg-indigo-600/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" stroke-width="1.8"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M16 12a4 4 0 10-8 0 4 4 0 008 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round"
                      d="M12 14v7m6-3H6"/>
            </svg>
        </div>

        <h1 class="text-2xl font-bold mb-2">{{ __('Entrar na sua conta') }}</h1>
        <p class="text-slate-600 text-sm">{{ __('Digite seu e-mail e senha para continuar') }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="text-center mb-4" :status="session('status')" />

    <!-- Formulário -->
    <form wire:submit="login" class="space-y-5">
        <!-- Email Address -->
        <div>
            <label class="block text-xs font-medium mb-1">{{ __('Email') }}</label>
            <input
                wire:model="email"
                type="email"
                required
                autofocus
                autocomplete="email"
                placeholder="email@exemplo.com"
                class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('email') border-red-500 @enderror"
            />
            @error('email')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1">
                <label class="block text-xs font-medium">{{ __('Senha') }}</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-indigo-600 hover:underline" wire:navigate>
                        {{ __('Esqueci minha senha') }}
                    </a>
                @endif
            </div>
            <input
                wire:model="password"
                type="password"
                required
                autocomplete="current-password"
                placeholder="{{ __('Senha') }}"
                class="w-full px-3 py-2.5 rounded-xl border border-slate-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('password') border-red-500 @enderror"
            />
            @error('password')
                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-2">
            <input
                wire:model="remember"
                type="checkbox"
                id="remember"
                class="rounded border-slate-300 text-indigo-600 focus:ring-2 focus:ring-indigo-500"
            />
            <label for="remember" class="text-xs">{{ __('Lembrar-me') }}</label>
        </div>

        <button
            type="submit"
            class="w-full py-3 rounded-xl bg-indigo-600 text-white font-semibold text-sm shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
        >
            {{ __('Entrar') }}
        </button>
    </form>

    @if (Route::has('register'))
        <div class="text-center text-xs text-slate-600 mt-6">
            {{ __('Não tem uma conta?') }}
            <a href="{{ route('register') }}" class="text-indigo-600 hover:underline font-medium" wire:navigate>
                {{ __('Criar conta') }}
            </a>
        </div>
    @endif

</div>
