<x-guest-layout maxWidth="sm:max-w-2xl">
    <div class="mb-8">
        <h2 class="text-2xl font-black text-slate-800 tracking-tight">Daftar Akun Baru</h2>
        <p class="text-sm font-medium text-slate-400 mt-1">Silakan lengkapi data pendaftaran Anda</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Baris 1: Nama + Email --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Name -->
            <div>
                <label for="name" class="block text-sm font-bold text-slate-700 mb-2">Nama Lengkap</label>
                <input id="name"
                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('name') border-rose-500 @enderror"
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Masukkan Nama Lengkap" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-bold text-slate-700 mb-2">Email</label>
                <input id="email"
                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('email') border-rose-500 @enderror"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autocomplete="username"
                    placeholder="Masukkan Email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
        </div>

        {{-- Baris 2: NPM + Program Studi --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- NPM -->
            <div>
                <label for="student_number" class="block text-sm font-bold text-slate-700 mb-2">NPM</label>
                <input id="student_number"
                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm font-mono focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('student_number') border-rose-500 @enderror"
                    type="text"
                    name="student_number"
                    value="{{ old('student_number') }}"
                    autocomplete="off"
                    placeholder="Masukkan NPM" />
                <p class="mt-1.5 text-xs text-slate-400">Bisa dikosongkan dan diisi nanti.</p>
                <x-input-error :messages="$errors->get('student_number')" class="mt-2" />
            </div>

            <!-- Program Studi -->
            <div>
                <label for="study_program" class="block text-sm font-bold text-slate-700 mb-2">Program Studi</label>
                <input id="study_program"
                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('study_program') border-rose-500 @enderror"
                    type="text"
                    name="study_program"
                    value="{{ old('study_program') }}"
                    autocomplete="off"
                    placeholder="Masukkan Program Studi" />
                <x-input-error :messages="$errors->get('study_program')" class="mt-2" />
            </div>
        </div>

        {{-- Baris 3: Password + Konfirmasi --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-bold text-slate-700 mb-2">Password</label>
                <input id="password"
                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('password') border-rose-500 @enderror"
                    type="password"
                    name="password"
                    required
                    autocomplete="new-password"
                    placeholder="Masukkan Password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-bold text-slate-700 mb-2">Konfirmasi Password</label>
                <input id="password_confirmation"
                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('password_confirmation') border-rose-500 @enderror"
                    type="password"
                    name="password_confirmation"
                    required
                    autocomplete="new-password"
                    placeholder="Ulangi Password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        <div class="pt-2">
            <button type="submit" class="w-full inline-flex justify-center items-center rounded-xl bg-blue-600 px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-500/40 transition-all transform hover:-translate-y-0.5 uppercase tracking-wider">
                Daftar
            </button>
        </div>

        <div class="pt-2 text-center">
            <a class="text-sm font-bold text-blue-600 hover:text-blue-700 transition" href="{{ route('login') }}">
                Sudah punya akun? Masuk
            </a>
        </div>
    </form>
</x-guest-layout>
