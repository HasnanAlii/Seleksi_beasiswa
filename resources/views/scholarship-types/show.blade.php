<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                Detail Jenis Beasiswa
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('scholarships.index') }}" class="hover:text-blue-600 cursor-pointer transition">Beasiswa</a>
                <span class="mx-2">/</span>
                <a href="{{ route('scholarship-types.index') }}" class="hover:text-blue-600 cursor-pointer transition">Jenis Beasiswa</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6 flex items-start justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-slate-800">{{ $scholarshipType->name }}</h3>
                            <p class="text-sm text-slate-400 mt-1">Ditambahkan: {{ $scholarshipType->created_at->format('d/m/Y') }}</p>
                        </div>
                        <a href="{{ route('scholarship-types.edit', $scholarshipType->id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white text-sm font-semibold rounded-xl shadow hover:bg-blue-700 transition-all">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Ubah
                        </a>
                    </div>

                    <dl class="space-y-5">
                        <div class="rounded-2xl bg-slate-50/70 px-5 py-4">
                            <dt class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Nama Jenis</dt>
                            <dd class="text-sm font-semibold text-slate-800">{{ $scholarshipType->name }}</dd>
                        </div>
                        <div class="rounded-2xl bg-slate-50/70 px-5 py-4">
                            <dt class="text-xs font-black uppercase tracking-widest text-slate-400 mb-1">Deskripsi</dt>
                            <dd class="text-sm text-slate-700 leading-relaxed">{{ $scholarshipType->description ?? '-' }}</dd>
                        </div>
                    </dl>

                    <div class="mt-10 flex items-center justify-between border-t border-slate-100 pt-6">
                        <a href="{{ route('scholarship-types.index') }}"
                            class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-800 transition">
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            Kembali
                        </a>
                        <form action="{{ route('scholarship-types.destroy', $scholarshipType->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                onclick="return confirm('Hapus jenis beasiswa ini?')"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-semibold text-rose-500 hover:bg-rose-50 rounded-xl transition-all">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
