<section>
    <header class="mb-8 border-b border-slate-100 pb-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="p-3 bg-amber-50 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    {{ __('Perbarui Password') }}
                </h2>
                <p class="text-sm font-medium text-slate-500">
                    {{ __('Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.') }}
                </p>
            </div>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-6 max-w-2xl">
        @csrf
        @method('put')

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Password Saat Ini</label>
            <input type="password" name="current_password" autocomplete="current-password"
                placeholder="Masukkan password saat ini"
                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 shadow-sm transition-all @error('current_password', 'updatePassword') border-rose-500 @enderror">
            @error('current_password', 'updatePassword')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Password Baru</label>
            <input type="password" name="password" autocomplete="new-password"
                placeholder="Masukkan password baru"
                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 shadow-sm transition-all @error('password', 'updatePassword') border-rose-500 @enderror">
            @error('password', 'updatePassword')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Konfirmasi Password Baru</label>
            <input type="password" name="password_confirmation" autocomplete="new-password"
                placeholder="Masukkan ulang password baru"
                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-amber-500 focus:ring-4 focus:ring-amber-500/10 shadow-sm transition-all @error('password_confirmation', 'updatePassword') border-rose-500 @enderror">
            @error('password_confirmation', 'updatePassword')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="mt-8 flex items-center gap-4 pt-6 border-t border-slate-100">
            <button type="submit"
                class="inline-flex justify-center rounded-2xl bg-amber-500 px-8 py-3.5 text-sm font-black text-white shadow-xl shadow-amber-500/20 hover:bg-amber-600 transition-all transform hover:-translate-y-0.5 uppercase tracking-widest">
                {{ __('Ganti Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm font-bold text-emerald-600 bg-emerald-50 px-4 py-2 rounded-xl border border-emerald-100"
                >✓ {{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>
</section>
