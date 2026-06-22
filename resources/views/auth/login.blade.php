<x-guest-layout>
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Selamat Datang Kembali</h2>
        <p class="text-sm font-medium text-slate-400 mt-1">Silakan masuk menggunakan akun Anda</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email</label>
            <input id="email" 
                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('email') border-rose-500 @enderror" 
                type="email" 
                name="email" 
                value="{{ old('email') }}" 
                required 
                autofocus 
                autocomplete="username" 
                placeholder="Masukkan Email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div x-data="{ showPass: false }">
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-blue-600 hover:text-blue-700 transition" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <div class="relative">
                <input id="password" 
                    class="w-full rounded-xl border-slate-200 bg-white pl-4 pr-10 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('password') border-rose-500 @enderror"
                    :type="showPass ? 'text' : 'password'"
                    name="password"
                    required 
                    autocomplete="current-password"
                    placeholder="Masukkan Password" />
                <button type="button" @click="showPass = !showPass" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 transition">
                    <!-- Eye icon (visible when password is hidden) -->
                    <svg x-show="!showPass" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <!-- Eye off icon (visible when password is shown) -->
                    <svg x-show="showPass" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="display: none;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                    </svg>
                </button>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        {{-- <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-200 text-blue-600 shadow-sm focus:ring-blue-500 focus:ring-offset-0 focus:ring-4 focus:ring-blue-500/10" name="remember">
                <span class="ms-2 text-sm font-semibold text-slate-600">Ingat saya</span>
            </label>
        </div> --}}

        <div class="pt-2">
            <button type="submit" class="w-full inline-flex justify-center items-center rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-500/40 transition-all transform hover:-translate-y-0.5 uppercase tracking-wider">
                Masuk
            </button>
        </div>
        <div class="pt-2 text-center">
            <span class="text-sm text-slate-500 font-semibold">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-sm font-bold text-blue-600 hover:text-blue-700 transition ml-1">Daftar di sini</a>
        </div>
    </form>
</x-guest-layout>
