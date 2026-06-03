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
        <div>
            <div class="flex justify-between items-center mb-2">
                <label for="password" class="block text-sm font-bold text-slate-700">Password</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-blue-600 hover:text-blue-700 transition" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <input id="password" 
                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('password') border-rose-500 @enderror"
                type="password"
                name="password"
                required 
                autocomplete="current-password"
                placeholder="Masukkan Password" />

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
