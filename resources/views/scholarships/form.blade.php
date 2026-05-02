<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">
                {{ $scholarship->exists ? 'Ubah Beasiswa' : 'Tambah Beasiswa' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('scholarships.index') }}" class="transition hover:text-blue-600">Beasiswa</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $scholarship->exists ? 'Ubah' : 'Tambah' }}</span>
            </nav>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#f0f6ff] px-10 py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Formulir Data Beasiswa</h3>
                        <p class="mt-1 text-sm text-slate-500">Lengkapi semua informasi beasiswa dengan lengkap dan benar.</p>
                    </div>

                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="space-y-6">

                            {{-- Nama Beasiswa --}}
                            <div>
                                <label for="scholarship_name" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Nama Beasiswa <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="scholarship_name" id="scholarship_name"
                                    value="{{ old('scholarship_name', $scholarship->scholarship_name) }}" required
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('scholarship_name') border-rose-500 @enderror"
                                    placeholder="Masukkan nama beasiswa">
                                @error('scholarship_name')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jenis Beasiswa --}}
                            <div>
                                <label for="scholarship_type" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Jenis Beasiswa <span class="text-rose-500">*</span>
                                </label>
                                <select name="scholarship_type" id="scholarship_type" required
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('scholarship_type') border-rose-500 @enderror">
                                    <option value="">Pilih jenis beasiswa</option>
                                    @foreach($scholarshipTypes as $type)
                                        <option value="{{ $type->name }}" {{ old('scholarship_type', $scholarship->scholarship_type) === $type->name ? 'selected' : '' }}>
                                            {{ $type->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('scholarship_type')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Ketentuan --}}
                            <div>
                                <label for="description" class="mb-2 block text-sm font-semibold text-slate-700">Ketentuan Beasiswa</label>
                                <textarea name="description" id="description" rows="5"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('description') border-rose-500 @enderror"
                                    placeholder="Masukkan ketentuan beasiswa">{{ old('description', $scholarship->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Persyaratan --}}
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <label class="block text-sm font-semibold text-slate-700">Persyaratan</label>
                                    <a href="{{ route('requirements.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                        Kelola Persyaratan
                                    </a>
                                </div>
                                <div class="space-y-2 rounded-xl border border-slate-200 bg-slate-50 p-4">
                                    @forelse($requirements as $requirement)
                                        <div class="rounded-lg bg-white px-3 py-3 text-sm text-slate-700 shadow-sm">
                                            <label class="flex items-start gap-3">
                                                <input type="checkbox" name="requirement_ids[]" value="{{ $requirement->id }}"
                                                    {{ in_array($requirement->id, old('requirement_ids', $selectedRequirementIds ?? [])) ? 'checked' : '' }}
                                                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                <span class="font-semibold text-slate-800">{{ $requirement->requirement_name }}</span>
                                            </label>
                                            <div class="mt-3 pl-7">
                                                <label class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                                    Ketentuan untuk syarat ini
                                                </label>
                                                <textarea name="requirement_terms[{{ $requirement->id }}]" rows="2"
                                                    placeholder="Masukkan ketentuan"
                                                    class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-xs text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10">{{ old('requirement_terms.' . $requirement->id, $requirementTerms[$requirement->id] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500">Belum ada data persyaratan. Silakan tambahkan terlebih dahulu.</p>
                                    @endforelse
                                </div>
                                @error('requirement_ids')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                                @error('requirement_ids.*')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                                @error('requirement_terms')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                                @error('requirement_terms.*')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Kuota & Periode --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="quota" class="mb-2 block text-sm font-semibold text-slate-700">
                                        Kuota Penerima <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" name="quota" id="quota" min="1"
                                        value="{{ old('quota', $scholarship->quota) }}" required
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('quota') border-rose-500 @enderror"
                                        placeholder="Masukkan jumlah kuota">
                                    @error('quota')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="validity_period" class="mb-2 block text-sm font-semibold text-slate-700">
                                        Batas Akhir Pendaftaran <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" name="validity_period" id="validity_period"
                                        value="{{ old('validity_period', $scholarship->validity_period ? $scholarship->validity_period->format('Y-m-d') : '') }}" required
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('validity_period') border-rose-500 @enderror">
                                    @error('validity_period')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('scholarships.index') }}"
                                class="inline-flex justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:-translate-y-0.5">
                                {{ $submitLabel }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
