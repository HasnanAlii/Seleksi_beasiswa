<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ $interview->exists ? 'Ubah Jadwal Wawancara' : 'Tambah Jadwal Wawancara' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('interviews.index') }}" class="hover:text-blue-600 cursor-pointer transition">Wawancara</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $interview->exists ? 'Ubah' : 'Tambah' }}</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Formulir Jadwal Wawancara</h3>
                        <p class="text-sm text-slate-500 mt-1">Isi jadwal dan pendaftar yang akan diwawancara.</p>
                    </div>

                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if($method === 'PUT')
                            @method('PUT')
                        @endif

                        <div class="space-y-6">
                            {{-- Pendaftaran --}}
                            <div>
                                <label for="application_id" class="block text-sm font-semibold text-slate-700 mb-2">Pendaftaran (Mahasiswa) <span class="text-rose-500">*</span></label>
                                <select id="application_id" name="application_id" required
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('application_id') border-rose-500 @enderror">
                                    <option value="">-- Pilih Pendaftar --</option>
                                    @foreach($applications as $app)
                                        <option value="{{ $app->id }}" {{ old('application_id', $interview->application_id) == $app->id ? 'selected' : '' }}>
                                            {{ $app->student->name }} — {{ $app->scholarship->scholarship_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('application_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jadwal --}}
                            <div>
                                <label for="schedule" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal & Waktu Wawancara <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" id="schedule" name="schedule" required
                                    value="{{ old('schedule', $interview->schedule ? $interview->schedule->format('Y-m-d\TH:i') : '') }}"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('schedule') border-rose-500 @enderror">
                                @error('schedule')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Catatan / Keterangan</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('description') border-rose-500 @enderror"
                                    placeholder="Opsional...">{{ old('description', $interview->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('interviews.index') }}"
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
