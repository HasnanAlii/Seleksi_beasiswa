<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">Detail Seleksi</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('selections.index') }}" class="hover:text-blue-600 cursor-pointer transition">Seleksi</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">

                {{-- Header Banner --}}
                @php
                    $statusColors = [
                        'diterima' => 'from-emerald-500 to-teal-600',
                        'wawancara' => 'from-blue-500 to-indigo-600',
                        'verifikasi' => 'from-amber-500 to-orange-500',
                        'tidak diterima' => 'from-rose-500 to-red-600',
                    ];
                    $statusLabels = [
                        'diterima' => 'Diterima',
                        'wawancara' => 'Wawancara',
                        'verifikasi' => 'Verifikasi',
                        'tidak diterima' => 'Tidak Diterima',
                    ];
                    $color = $statusColors[$selection->status] ?? 'from-slate-500 to-slate-600';
                    $label = $statusLabels[$selection->status] ?? ucfirst($selection->status);
                @endphp
                <div class="bg-gradient-to-r {{ $color }} px-8 py-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="bg-white/20 text-white text-xs font-bold px-3 py-1 rounded-full border border-white/30 backdrop-blur-sm">Seleksi #{{ $selection->id }}</span>
                            <span class="inline-flex items-center gap-1.5 bg-white/25 text-white text-xs font-bold px-3 py-1 rounded-full backdrop-blur-sm">
                                <div class="w-1.5 h-1.5 rounded-full bg-white"></div>
                                {{ $label }}
                            </span>
                        </div>
                        <h3 class="text-3xl font-extrabold">{{ $selection->application->student->name }}</h3>
                        <p class="text-white/80 mt-2 font-medium text-lg">{{ $selection->application->scholarship->scholarship_name }}</p>
                        <p class="text-white/60 text-sm mt-1">Tahap: <span class="font-semibold text-white/90">{{ $selection->stage }}</span></p>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8 md:p-10 space-y-8">

                    {{-- Info Mahasiswa --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Informasi Mahasiswa</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Nama</div>
                                <div class="text-base font-bold text-slate-800">{{ $selection->application->student->name }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">NIM</div>
                                <div class="text-base font-bold text-slate-800">{{ $selection->application->student->student_number }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Program Studi</div>
                                <div class="text-base font-bold text-slate-800">{{ $selection->application->student->study_program }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Program Beasiswa</div>
                                <div class="text-base font-bold text-slate-800">{{ $selection->application->scholarship->scholarship_name }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Seleksi --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Detail Seleksi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Tahap</div>
                                <div class="text-base font-bold text-slate-800">{{ $selection->stage }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Tanggal Seleksi</div>
                                <div class="text-base font-bold text-slate-800">{{ \Carbon\Carbon::parse($selection->date)->translatedFormat('l, d F Y H:i') }} WIB</div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="text-xs font-semibold text-slate-400 mb-1">Catatan</div>
                                <div class="text-base font-medium text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    {{ $selection->notes ?: 'Tidak ada catatan.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Footer --}}
                <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex justify-between items-center">
                    <a href="{{ route('selections.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                    <a href="{{ route('selections.edit', $selection->id) }}"
                        class="inline-flex justify-center items-center rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:bg-amber-600 transition-all transform hover:-translate-y-0.5">
                        Ubah Data
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
