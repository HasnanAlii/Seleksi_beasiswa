<section>
    <header class="mb-8 border-b border-slate-100 pb-6">
        <div class="flex items-center gap-4 mb-2">
            <div class="p-3 bg-blue-50 rounded-2xl">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tight">
                    {{ __('Informasi Profil') }}
                </h2>
                <p class="text-sm font-medium text-slate-500">
                    {{ __("Perbarui informasi profil akun dan alamat email Anda.") }}
                </p>
            </div>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-6 max-w-2xl">
        @csrf
        @method('patch')

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap</label>
            <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('name') border-rose-500 @enderror">
            @error('name')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-semibold text-slate-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('email') border-rose-500 @enderror">
            @error('email')
                <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-4 bg-amber-50 rounded-xl border border-amber-200">
                    <p class="text-sm font-medium text-amber-800">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="mt-1 font-bold underline text-sm text-amber-900 hover:text-amber-700 rounded-md focus:outline-none transition-colors">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-3 font-semibold text-sm text-emerald-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @hasrole('mahasiswa')
        {{-- Section khusus data mahasiswa --}}
        <div class="pt-6 mt-8 border-t border-slate-100">
            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z" />
                </svg>
                Data Mahasiswa
            </h3>

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">NPM / Nomor Pokok Mahasiswa</label>
                    <input type="text" name="student_number" value="{{ old('student_number', $user->student?->student_number) }}" placeholder="Masukan NPM"
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm font-mono focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('student_number') border-rose-500 @enderror">
                    @error('student_number')
                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Program Studi</label>
                    <input type="text" name="study_program" value="{{ old('study_program', $user->student?->study_program) }}" placeholder="MAsukan Program Studi"
                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('study_program') border-rose-500 @enderror">
                    @error('study_program')
                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        @endhasrole

        <div class="mt-8 flex items-center gap-4 pt-6 border-t border-slate-100">
            <button type="submit"
                class="inline-flex justify-center rounded-2xl bg-blue-600 px-8 py-3.5 text-sm font-black text-white shadow-xl shadow-blue-600/20 hover:bg-blue-700 transition-all transform hover:-translate-y-0.5 uppercase tracking-widest">
                {{ __('Simpan Perubahan') }}
            </button>

            @if (session('status') === 'profile-updated')
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
