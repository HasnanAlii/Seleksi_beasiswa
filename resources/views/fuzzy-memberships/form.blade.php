<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ $membership->exists ? 'Ubah Fungsi Keanggotaan' : 'Tambah Fungsi Keanggotaan' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('fuzzy-memberships.index') }}" class="hover:text-blue-600 cursor-pointer transition">Fungsi Keanggotaan</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $membership->exists ? 'Ubah' : 'Tambah' }}</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Formulir Fungsi Keanggotaan</h3>
                        <p class="text-sm text-slate-500 mt-1">Tentukan nilai batas bawah, tengah, dan atas untuk setiap label kriteria fuzzy.</p>
                    </div>

                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if($method === 'PUT') @method('PUT') @endif

                        <div class="space-y-6">

                            {{-- Kriteria --}}
                            <div>
                                <label for="criteria_id" class="block text-sm font-semibold text-slate-700 mb-2">Kriteria Fuzzy <span class="text-rose-500">*</span></label>
                                <select id="criteria_id" name="criteria_id" required
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('criteria_id') border-rose-500 @enderror">
                                    <option value="">-- Pilih Kriteria --</option>
                                    @foreach($criteriaList as $c)
                                        <option value="{{ $c->id }}" {{ old('criteria_id', $membership->criteria_id) == $c->id ? 'selected' : '' }}>
                                            {{ $c->criteria_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('criteria_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Label --}}
                            <div>
                                <label for="label" class="block text-sm font-semibold text-slate-700 mb-2">Label <span class="text-rose-500">*</span></label>
                                <div class="grid grid-cols-3 gap-3">
                                    @foreach(['rendah' => ['rose', 'Rendah'], 'sedang' => ['amber', 'Sedang'], 'tinggi' => ['emerald', 'Tinggi']] as $val => [$color, $text])
                                    <label class="cursor-pointer">
                                        <input type="radio" name="label" value="{{ $val }}" class="sr-only peer"
                                            {{ old('label', $membership->label) == $val ? 'checked' : '' }} required>
                                        <div class="flex items-center justify-center py-3 rounded-xl border-2 border-slate-200 font-bold text-sm text-slate-500
                                            peer-checked:border-{{ $color }}-500 peer-checked:bg-{{ $color }}-50 peer-checked:text-{{ $color }}-700
                                            hover:border-{{ $color }}-300 transition-all">
                                            {{ $text }}
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                                @error('label')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nilai Min, Mid, Max --}}
                            <div class="grid grid-cols-3 gap-4">
                                <div>
                                    <label for="min_value" class="block text-sm font-semibold text-slate-700 mb-2">Nilai Min <span class="text-rose-500">*</span></label>
                                    <input type="number" id="min_value" name="min_value" required step="any"
                                        value="{{ old('min_value', $membership->min_value) }}"
                                        placeholder="0"
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('min_value') border-rose-500 @enderror">
                                    @error('min_value')
                                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="mid_value" class="block text-sm font-semibold text-slate-700 mb-2">Nilai Mid <span class="text-rose-500">*</span></label>
                                    <input type="number" id="mid_value" name="mid_value" required step="any"
                                        value="{{ old('mid_value', $membership->mid_value) }}"
                                        placeholder="0"
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('mid_value') border-rose-500 @enderror">
                                    @error('mid_value')
                                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="max_value" class="block text-sm font-semibold text-slate-700 mb-2">Nilai Max <span class="text-rose-500">*</span></label>
                                    <input type="number" id="max_value" name="max_value" required step="any"
                                        value="{{ old('max_value', $membership->max_value) }}"
                                        placeholder="0"
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('max_value') border-rose-500 @enderror">
                                    @error('max_value')
                                        <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('fuzzy-memberships.index') }}"
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
