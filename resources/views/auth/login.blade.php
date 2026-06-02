<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white">Selamat Datang Kembali</h2>
        <p class="text-sm text-slate-500 dark:text-zinc-400 mt-1.5">Silakan masuk ke akun Anda</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700 dark:text-zinc-300 font-medium" />
            <div class="mt-1 relative rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <!-- Envelope icon -->
                    <svg class="h-5 w-5 text-slate-400 dark:text-zinc-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                    </svg>
                </div>
                <x-text-input id="email" class="block w-full pl-10 pr-3 py-2.5 border-slate-200 dark:border-zinc-800 dark:bg-zinc-950/50 dark:text-white focus:ring-violet-500 focus:border-violet-500 focus:ring-2 focus:ring-opacity-20 rounded-lg text-sm transition-all duration-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-red-500" />
        </div>

        <!-- Password -->
        <div class="mt-5" x-data="{ show: false }">
            <div class="flex items-center justify-between">
                <x-input-label for="password" :value="__('Password')" class="text-slate-700 dark:text-zinc-300 font-medium" />
                @if (Route::has('password.request'))
                    <a class="text-xs font-semibold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 hover:underline transition-colors" href="{{ route('password.request') }}">
                        {{ __('Forgot your password?') }}
                    </a>
                @endif
            </div>
            <div class="mt-1 relative rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <!-- Lock icon -->
                    <svg class="h-5 w-5 text-slate-400 dark:text-zinc-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <x-text-input id="password" class="block w-full pl-10 pr-10 py-2.5 border-slate-200 dark:border-zinc-800 dark:bg-zinc-950/50 dark:text-white focus:ring-violet-500 focus:border-violet-500 focus:ring-2 focus:ring-opacity-20 rounded-lg text-sm transition-all duration-200"
                               ::type="show ? 'text' : 'password'"
                               name="password"
                               required autocomplete="current-password" placeholder="••••••••" />
                <!-- Toggle visibility button -->
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 dark:text-zinc-500 hover:text-slate-600 dark:hover:text-zinc-300 focus:outline-none transition-colors">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="!show">
                        <!-- Eye icon -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" x-show="show" style="display: none;">
                        <!-- Eye-off icon -->
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L5.146 5.146m9.708 9.708l4.708 4.708M2.458 12a9.98 9.98 0 011.83-3.217m10.993-3.593A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-2.25 3.523M3 3l18 18" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-red-500" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-5">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 dark:border-zinc-800 text-violet-600 focus:ring-violet-500 focus:ring-offset-0 bg-white dark:bg-zinc-950 transition-colors" name="remember">
                <span class="ms-2 text-sm text-slate-500 dark:text-zinc-400 select-none">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="mt-6 flex flex-col gap-4">
            <button type="submit" class="w-full flex justify-center items-center py-2.5 px-4 border border-transparent rounded-lg text-sm font-semibold text-white bg-gradient-to-r from-violet-600 to-indigo-600 hover:from-violet-700 hover:to-indigo-700 active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 shadow-md hover:shadow-lg hover:shadow-indigo-500/10 transition-all duration-200">
                {{ __('Log in') }}
            </button>

            @if (Route::has('register'))
                <div class="text-center text-xs text-slate-500 dark:text-zinc-400 mt-2">
                    <span>Belum punya akun?</span>
                    <a href="{{ route('register') }}" class="font-semibold text-violet-600 dark:text-violet-400 hover:text-violet-700 dark:hover:text-violet-300 hover:underline ms-1 transition-colors">
                        Daftar sekarang
                    </a>
                </div>
            @endif
        </div>
    </form>
</x-guest-layout>

