<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">Detail Kriteria Fuzzy</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('fuzzy-criteria.index') }}" class="hover:text-blue-600 cursor-pointer transition">Kriteria Fuzzy</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">

                {{-- Header Banner --}}
                <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-8 py-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full border border-white/30 backdrop-blur-sm">Kriteria #{{ $criteria->id }}</span>
                            <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full border border-white/30">
                                {{ $criteria->memberships->count() }} Keanggotaan
                            </span>
                        </div>
                        <h3 class="text-3xl font-extrabold">{{ $criteria->criteria_name }}</h3>
                        <p class="text-indigo-100 mt-2 text-sm">Ditambahkan pada {{ $criteria->created_at->translatedFormat('d F Y') }}</p>
                    </div>
                </div>

                {{-- Fungsi Keanggotaan --}}
                <div class="p-8 md:p-10">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest">Fungsi Keanggotaan ({{ $criteria->memberships->count() }})</h4>
                        <a href="{{ route('fuzzy-memberships.create') }}"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-indigo-600 hover:text-indigo-800 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                            </svg>
                            Tambah Keanggotaan
                        </a>
                    </div>
                    <div class="border-b border-slate-100 mb-6"></div>

                    @if($criteria->memberships->count() > 0)
                        <div class="space-y-3">
                            @foreach($criteria->memberships as $membership)
                            <div class="flex items-center justify-between bg-slate-50 rounded-xl px-5 py-4 border border-slate-100">
                                <div>
                                    @php
                                        $labelColors = [
                                            'rendah' => 'bg-rose-100 text-rose-700',
                                            'sedang' => 'bg-amber-100 text-amber-700',
                                            'tinggi' => 'bg-emerald-100 text-emerald-700',
                                        ];
                                        $badgeClass = $labelColors[$membership->label] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                        <div class="w-2 h-2 rounded-full {{ match($membership->label) { 'tinggi' => 'bg-emerald-500', 'rendah' => 'bg-rose-500', default => 'bg-amber-500' } }}"></div>
                                        {{ ucfirst($membership->label) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-6 text-sm">
                                    <div class="text-center">
                                        <div class="text-xs text-slate-400 mb-0.5">Min</div>
                                        <div class="font-bold text-slate-700">{{ number_format($membership->min_value, 2) }}</div>
                                    </div>
                                    <div class="text-slate-300">→</div>
                                    <div class="text-center">
                                        <div class="text-xs text-slate-400 mb-0.5">Mid</div>
                                        <div class="font-bold text-slate-700">{{ number_format($membership->mid_value, 2) }}</div>
                                    </div>
                                    <div class="text-slate-300">→</div>
                                    <div class="text-center">
                                        <div class="text-xs text-slate-400 mb-0.5">Max</div>
                                        <div class="font-bold text-slate-700">{{ number_format($membership->max_value, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-10 text-center">
                            <div class="bg-slate-50 p-4 rounded-full mb-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-slate-600">Belum ada fungsi keanggotaan</p>
                            <p class="text-xs text-slate-400 mt-1">Tambahkan nilai rendah, sedang, dan tinggi untuk kriteria ini.</p>
                        </div>
                    @endif
                </div>

                {{-- Footer --}}
                <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex justify-between items-center">
                    <a href="{{ route('fuzzy-criteria.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('fuzzy-criteria.edit', $criteria->id) }}"
                        class="inline-flex justify-center items-center rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:bg-amber-600 transition-all transform hover:-translate-y-0.5">
                        Ubah Data
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
