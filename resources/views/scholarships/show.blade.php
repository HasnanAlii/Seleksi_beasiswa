<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Detail Beasiswa') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('scholarships.index') }}" class="hover:text-blue-600 cursor-pointer transition">Beasiswa</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail Data</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8 max-w-6xl">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kiri: Detail Utama -->
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                        <div class="p-8 text-center bg-gradient-to-br from-blue-600 to-indigo-700 text-white relative overflow-hidden">
                            <!-- Dekorasi pattern -->
                            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
                            
                            <div class="relative z-10">
                                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center mx-auto mb-4 backdrop-blur-md border border-white/30 shadow-inner">
                                    <svg class="w-9 h-9 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l9-5-9-5-9 5 9 5z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 14l10 3" />
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-black leading-tight mb-2">{{ $scholarship->scholarship_name }}</h3>
                                <span class="inline-block px-3 py-1 bg-white/20 backdrop-blur-sm border border-white/20 rounded-full text-xs font-bold tracking-widest uppercase">
                                    {{ $scholarship->scholarship_type }}
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <div class="space-y-4">
                                {{-- <div class="flex justify-between items-center border-b border-slate-50 pb-3">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">ID Beasiswa</span>
                                    <span class="font-mono text-sm font-bold text-slate-800">#{{ str_pad($scholarship->id, 5, '0', STR_PAD_LEFT) }}</span>
                                </div> --}}
                                <div class="flex justify-between items-center border-b border-slate-50 pb-3">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kuota Disediakan</span>
                                    <span class="text-sm font-bold text-slate-800">{{ $scholarship->quota }} Orang</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-50 pb-3">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Batas Pendaftaran</span>
                                    <span class="text-sm font-bold text-slate-800">{{ $scholarship->validity_period ? $scholarship->validity_period->format('d/m/Y') : '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center pt-1">
                                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Periode</span>
                                    @php
                                        $isExpired = $scholarship->validity_period && $scholarship->validity_period->isPast();
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-xs font-bold {{ $isExpired ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                                        <div class="w-2 h-2 rounded-full {{ $isExpired ? 'bg-rose-500' : 'bg-emerald-500' }}"></div>
                                        {{ $isExpired ? 'Berakhir' : 'Aktif' }}
                                    </span>
                                </div>
                            </div>
                            
                            @hasrole('admin|staf')
                            <div class="mt-8 pt-6 border-t border-slate-100">
                                <a href="{{ route('scholarships.edit', $scholarship) }}" class="block w-full py-3 bg-slate-900 text-white text-center rounded-xl text-xs font-bold uppercase tracking-widest shadow-xl shadow-slate-200 hover:bg-blue-600 transition-all duration-300 transform hover:-translate-y-1">
                                    Edit Detail Beasiswa
                                </a>
                            </div>
                            @endhasrole
                        </div>
                    </div>
                </div>

                <!-- Kanan: Statistik & Relasi -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Stats Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/60 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                <svg class="w-32 h-32 text-blue-600" fill="currentColor" viewBox="0 0 20 20"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Persyaratan</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-slate-800">{{ $scholarship->requirements->count() }}</span>
                                <span class="text-sm font-semibold text-slate-500">Syarat</span>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/60 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                <svg class="w-32 h-32 text-indigo-600" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Pendaftar</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-slate-800">{{ $scholarship->applications->count() }}</span>
                                <span class="text-sm font-semibold text-slate-500">Orang</span>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/60 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 -mr-4 -mt-4 opacity-5 group-hover:opacity-10 transition-opacity">
                                <svg class="w-32 h-32 text-emerald-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Pengumuman Terkait</span>
                            <div class="flex items-baseline gap-2">
                                <span class="text-4xl font-black text-slate-800">{{ $scholarship->announcements->count() }}</span>
                                <span class="text-sm font-semibold text-slate-500">Info</span>
                            </div>
                        </div>
                    </div>

                    <!-- Persyaratan List Preview -->
                    <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                            <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Persyaratan Dokumen</h4>
                             @hasrole('admin|staf')
                            <a href="#" class="text-xs font-bold text-blue-600 hover:text-blue-800">Kelola Persyaratan &rarr;</a>
                             @endhasrole
                        </div>
                        <div class="p-0">
                            @if($scholarship->requirements->count() > 0)
                                <ul class="divide-y divide-slate-50">
                                    @foreach($scholarship->requirements->take(5) as $req)
                                        <li class="p-4 hover:bg-slate-50 flex items-start gap-3">
                                            <div class="mt-0.5 text-blue-500">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-700">{{ $req->requirement->requirement_name ?? 'Syarat' }}</p>
                                                <p class="text-xs text-slate-500 mt-0.5">{{ $req->terms ?: 'Tanpa ketentuan khusus' }}</p>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="p-10 text-center">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-50 mb-3 text-slate-400">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-500">Belum ada persyaratan</p>
                                    <p class="text-xs text-slate-400 mt-1">Tambahkan persyaratan untuk beasiswa ini</p>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
