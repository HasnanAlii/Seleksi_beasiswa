<section class="space-y-6">
    <header class="mb-8 border-b border-rose-100 pb-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="p-3 bg-rose-50 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-rose-600 tracking-tight">
                    {{ __('Hapus Akun') }}
                </h2>
                <p class="text-sm font-medium text-rose-500/80">
                    {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.') }}
                </p>
            </div>
        </div>
    </header>

    <div class="max-w-2xl">
        <div class="bg-rose-50/50 rounded-2xl border border-rose-100 p-6 mb-6">
            <p class="text-sm text-rose-600 font-semibold leading-relaxed">
                <span class="block mb-2 font-black uppercase tracking-widest text-rose-500 text-[10px]">Peringatan!</span>
                Pastikan Anda telah mengunduh data atau informasi yang ingin Anda simpan sebelum menghapus akun. Tindakan ini tidak dapat dibatalkan.
            </p>
        </div>

        <button
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex justify-center rounded-2xl bg-white px-8 py-3.5 text-sm font-black text-rose-600 shadow-sm ring-1 ring-inset ring-rose-200 hover:bg-rose-50 transition-all uppercase tracking-widest"
        >
            {{ __('Hapus Akun Permanen') }}
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-black text-slate-800 tracking-tight mb-3">
                {{ __('Apakah Anda yakin ingin menghapus akun?') }}
            </h2>

            <p class="text-sm font-medium text-slate-500 mb-6 leading-relaxed">
                {{ __('Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun Anda secara permanen.') }}
            </p>

            <div class="mt-6 bg-slate-50/50 p-6 rounded-2xl border border-slate-100">
                <label class="block text-sm font-semibold text-slate-700 mb-2">Password Anda</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 shadow-sm transition-all @error('password', 'userDeletion') border-rose-500 @enderror"
                    placeholder="{{ __('Masukkan Password') }}"
                />
                @error('password', 'userDeletion')
                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                @enderror
            </div>

            <div class="mt-8 flex justify-end gap-3 pt-6 border-t border-slate-100">
                <button type="button" x-on:click="$dispatch('close')"
                    class="inline-flex justify-center rounded-2xl bg-white px-6 py-3 text-sm font-black text-slate-600 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-all uppercase tracking-widest">
                    {{ __('Batal') }}
                </button>

                <button type="submit"
                    class="inline-flex justify-center rounded-2xl bg-rose-600 px-6 py-3 text-sm font-black text-white shadow-xl shadow-rose-600/20 hover:bg-rose-700 transition-all transform hover:-translate-y-0.5 uppercase tracking-widest">
                    {{ __('Ya, Hapus Akun') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
