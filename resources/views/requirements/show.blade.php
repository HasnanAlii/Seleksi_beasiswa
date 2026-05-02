<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">Detail Persyaratan</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('requirements.index') }}" class="hover:text-blue-600 cursor-pointer transition">Persyaratan</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">

                {{-- Header Banner --}}
                <div class="bg-gradient-to-r from-violet-600 to-purple-600 px-8 py-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full border border-white/30 backdrop-blur-sm">Persyaratan #{{ $requirement->id }}</span>
                            <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full border border-white/30">
                                {{ $requirement->scholarshipRequirements->count() }} Beasiswa
                            </span>
                        </div>
                        <h3 class="text-3xl font-extrabold">{{ $requirement->requirement_name }}</h3>
                        <p class="text-violet-100 mt-2 text-sm">Ditambahkan pada {{ $requirement->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8 md:p-10 space-y-8">

                    {{-- Beasiswa yang menggunakan persyaratan ini --}}
                    @if($requirement->scholarshipRequirements->count() > 0)
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">
                            Digunakan oleh Beasiswa ({{ $requirement->scholarshipRequirements->count() }})
                        </h4>
                        <div class="space-y-3">
                            @foreach($requirement->scholarshipRequirements as $sr)
                            <div class="flex items-center justify-between bg-slate-50 rounded-xl px-5 py-4 border border-slate-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-2 h-2 rounded-full bg-violet-500"></div>
                                    <div class="font-semibold text-slate-800 text-sm">{{ $sr->scholarship->scholarship_name }}</div>
                                </div>
                                <span class="inline-flex items-center rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                                    {{ $sr->terms ?: 'Tanpa ketentuan khusus' }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Footer --}}
                <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex justify-between items-center">
                    <a href="{{ route('requirements.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('requirements.edit', $requirement->id) }}"
                        class="inline-flex justify-center items-center rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:bg-amber-600 transition-all transform hover:-translate-y-0.5">
                        Ubah Data
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
