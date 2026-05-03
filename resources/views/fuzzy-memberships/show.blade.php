<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">Detail Fungsi Keanggotaan</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('fuzzy-memberships.index') }}" class="hover:text-blue-600 cursor-pointer transition">Fungsi Keanggotaan</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">

                {{-- Header Banner --}}
                @php
                    $bannerClass = 'from-blue-500 to-indigo-600';
                @endphp
                <div class="bg-gradient-to-r {{ $bannerClass }} px-8 py-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <div class="relative z-10 flex items-center justify-between">
                        <div>
                            <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full border border-white/30 backdrop-blur-sm mb-4 inline-block">Keanggotaan #{{ $membership->id }}</span>
                            <h3 class="text-3xl font-extrabold">{{ $membership->criteria->criteria_name }}</h3>
                        </div>
                        <div class="text-right bg-white/10 rounded-2xl px-6 py-4 backdrop-blur-sm border border-white/20">
                            <div class="flex items-center gap-4 text-white">
                                <div class="text-center">
                                    <div class="text-xs text-white/60 mb-1">Min</div>
                                    <div class="text-xl font-extrabold">{{ number_format($membership->min_value, 2) }}</div>
                                </div>
                                <div class="text-white/40 text-lg">→</div>
                                <div class="text-center">
                                    <div class="text-xs text-white/60 mb-1">Mid</div>
                                    <div class="text-xl font-extrabold">{{ number_format($membership->mid_value, 2) }}</div>
                                </div>
                                <div class="text-white/40 text-lg">→</div>
                                <div class="text-center">
                                    <div class="text-xs text-white/60 mb-1">Max</div>
                                    <div class="text-xl font-extrabold">{{ number_format($membership->max_value, 2) }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8 md:p-10 space-y-8">
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Detail Keanggotaan</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Kriteria Fuzzy</div>
                                <div class="text-base font-bold text-slate-800">{{ $membership->criteria->criteria_name }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Beasiswa</div>
                                <div class="text-base font-bold text-slate-800">{{ $membership->scholarship->scholarship_name }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Visualisasi Nilai --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Nilai Batas Fungsi</h4>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 text-center">
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Batas Bawah (Min)</div>
                                <div class="text-3xl font-extrabold text-slate-800">{{ number_format($membership->min_value, 2) }}</div>
                            </div>
                            <div class="bg-blue-50 rounded-2xl p-5 border border-blue-100 text-center">
                                <div class="text-xs font-bold text-blue-400 uppercase tracking-widest mb-2">Nilai Tengah (Mid)</div>
                                <div class="text-3xl font-extrabold text-blue-700">{{ number_format($membership->mid_value, 2) }}</div>
                            </div>
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-100 text-center">
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Batas Atas (Max)</div>
                                <div class="text-3xl font-extrabold text-slate-800">{{ number_format($membership->max_value, 2) }}</div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="text-xs font-semibold text-slate-400 mb-1">Ditambahkan Pada</div>
                        <div class="text-sm font-medium text-slate-700">{{ $membership->created_at->translatedFormat('l, d F Y H:i') }} WIB</div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex justify-between items-center">
                    <a href="{{ route('fuzzy-memberships.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('fuzzy-memberships.edit', $membership->id) }}"
                        class="inline-flex justify-center items-center rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:bg-amber-600 transition-all transform hover:-translate-y-0.5">
                        Ubah Data
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
