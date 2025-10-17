<x-guest-layout>
    <!-- Islamic Greeting -->
    <div class="text-center mb-6">
        <p class="arabic-text" style="font-size: 1.5rem; font-family: 'Times New Roman', serif; direction: rtl;">
            السلام عليكم ورحمة الله وبركاته
        </p>
        <div class="gold-divider"></div>
    </div>

    <!-- Page Title -->
    <div class="text-center mb-6">
        <h3 class="text-2xl font-bold" style="color: #2D5F3F;">Masuk ke Sistem SIAKAD</h3>
        <p class="text-sm text-gray-600 mt-2">Silakan masuk dengan akun Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Username -->
        <div>
            <x-input-label for="username" value="Username" />
            <x-text-input id="username" class="block mt-2 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="Masukkan username Anda" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <x-input-label for="password" value="Password" />
            <div class="relative">
                <input id="password"
                       :type="show ? 'text' : 'password'"
                       name="password"
                       required
                       autocomplete="current-password"
                       placeholder="Masukkan password Anda"
                       class="border-gray-300 rounded-md shadow-sm islamic-input w-full pr-10 block mt-2"
                       style="border-color: #d1d5db;" />
                <button type="button"
                        @click="show = !show"
                        class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                        style="margin-top: 4px;">
                    <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="islamic-checkbox" name="remember" style="border-color: #d1d5db;">
                <span class="ms-2 text-sm text-gray-600">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="islamic-link text-sm" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="mt-6">
            <x-primary-button class="w-full">
                Masuk
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="text-center mt-6">
            <div class="gold-divider" style="margin: 1.5rem 0;"></div>
            <p class="text-sm text-gray-600">
                Belum punya akun?
                <a href="{{ route('register') }}" class="islamic-link font-semibold">
                    Daftar di sini
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
