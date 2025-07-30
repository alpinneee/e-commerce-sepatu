@php
use Illuminate\Support\Facades\Route;
@endphp

<x-guest-layout>
    <div class="text-center mb-8 animate-fade-in">
        <h2 class="text-2xl font-bold text-gray-900">Masuk ke Akun</h2>
        <p class="mt-2 text-gray-500">Silakan masuk dengan email dan password Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-6 animate-slide-up">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-sm font-medium text-gray-700" />
            <x-text-input id="email" class="block mt-2 w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring-gray-500 transition-all duration-300 hover:shadow-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-sm font-medium text-gray-700" />

            <x-text-input id="password" class="block mt-2 w-full rounded-md border-gray-300 focus:border-gray-500 focus:ring-gray-500 transition-all duration-300 hover:shadow-md"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-400 text-gray-600 focus:ring-gray-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-500 hover:text-gray-700 underline" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <x-primary-button class="w-full justify-center bg-gray-600 hover:bg-gray-700 focus:ring-gray-400 transition-all duration-300 hover:scale-105 hover:shadow-lg">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <div class="text-center">
            <p class="text-sm text-gray-500">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-medium text-gray-700 hover:text-gray-900 hover:underline">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>