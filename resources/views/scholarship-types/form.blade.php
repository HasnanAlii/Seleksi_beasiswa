<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ $scholarshipType->exists ? 'Ubah Jenis Beasiswa' : 'Tambah Jenis Beasiswa' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('scholarships.index') }}" class="hover:text-blue-600 cursor-pointer transition">Beasiswa</a>
                <span class="mx-2">/</span>
                <a href="{{ route('scholarship-types.index') }}" class="hover:text-blue-600 cursor-pointer transition">Jenis Beasiswa</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $scholarshipType->exists ? 'Ubah' : 'Tambah' }}</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Formulir Jenis Beasiswa</h3>
                        <p class="text-sm text-slate-500 mt-1">Isi data jenis/kategori beasiswa di bawah ini.</p>
                    </div>

                    <form action="{{ $action }}" method="POST">
                        @csrf
                        @if($method === 'PUT')
                            @method('PUT')
                        @endif

                        <div class="space-y-6">
                            {{-- Nama Jenis --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Nama Jenis Beasiswa <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="name" required
                                    value="{{ old('name', $scholarshipType->name) }}"
                                    placeholder="Masukkan nama jenis beasiswa"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('name') border-rose-500 focus:ring-rose-500/10 @enderror">
                                @error('name')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Deskripsi --}}
                            <div>
                                <label class="block text-sm font-semibold text-slate-700 mb-2">
                                    Deskripsi <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                                </label>
                                <textarea name="description" rows="4"
                                    placeholder="Keterangan singkat mengenai jenis beasiswa ini..."
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('description') border-rose-500 focus:ring-rose-500/10 @enderror">{{ old('description', $scholarshipType->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('scholarship-types.index') }}"
                                class="inline-flex justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 hover:text-slate-900 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all transform hover:-translate-y-0.5">
                                {{ $submitLabel }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
