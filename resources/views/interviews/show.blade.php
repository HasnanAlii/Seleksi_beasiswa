<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                Detail Jadwal Wawancara
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('interviews.index') }}" class="hover:text-blue-600 cursor-pointer transition">Wawancara</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">

                {{-- Header Banner --}}
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-8 py-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <h3 class="text-3xl font-extrabold tracking-tight">{{ $interview->application->student->name }}</h3>
                            <p class="text-indigo-100 mt-1 font-semibold text-lg">{{ $interview->application->scholarship->scholarship_name }}</p>
                        </div>

                        <div class="flex flex-col items-end">
                            @if($interview->assessments->count() > 0)
                                <span class="inline-flex items-center gap-2 bg-white text-emerald-600 text-sm font-black px-6 py-3 rounded-2xl shadow-xl shadow-black/10">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    SUDAH DINILAI
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 bg-white text-amber-600 text-sm font-black px-6 py-3 rounded-2xl shadow-xl shadow-black/10">
                                    <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                                    BELUM DINILAI
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8 md:p-10 space-y-8">

                    {{-- Info Wawancara --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Informasi Wawancara</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Mahasiswa</div>
                                <div class="text-base font-bold text-slate-800">{{ $interview->application->student->name }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $interview->application->student->student_number }} · {{ $interview->application->student->study_program }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Program Beasiswa</div>
                                <div class="text-base font-bold text-slate-800">{{ $interview->application->scholarship->scholarship_name }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Tanggal Wawancara</div>
                                <div class="text-base font-bold text-slate-800">{{ $interview->schedule->translatedFormat('l, d F Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Waktu</div>
                                <div class="text-base font-bold text-slate-800">{{ $interview->schedule->format('H:i') }} WIB</div>
                            </div>
                            <div class="md:col-span-2">
                                <div class="text-xs font-semibold text-slate-400 mb-1">Catatan</div>
                                <div class="text-base font-medium text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    {{ $interview->description ?: 'Tidak ada catatan.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Penilaian --}}
                    @if($interview->assessments->count() > 0)
                    <div>
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Hasil Penilaian Wawancara</h4>
                        <div class="space-y-4">
                            @foreach($interview->assessments as $assessment)
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm hover:shadow-md transition-shadow">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-0.5">Penilai / Interviewer</div>
                                                <div class="text-base font-bold text-slate-800">{{ $assessment->interviewer }}</div>
                                            </div>
                                        </div>
                                        <div class="text-left md:text-right">
                                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-0.5">Nilai Skor</div>
                                            <div class="text-3xl font-black text-indigo-600">{{ number_format($assessment->score, $assessment->score == floor($assessment->score) ? 0 : 2) }}</div>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-6 pt-6 border-t border-slate-100">
                                        <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-2">Catatan Penilaian</div>
                                        <div class="bg-slate-50 p-4 rounded-xl text-sm text-slate-600 italic leading-relaxed border border-slate-100">
                                            "{{ $assessment->notes ?: 'Tidak ada catatan khusus yang diberikan.' }}"
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Footer --}}
                <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex justify-between items-center">
                    <a href="{{ route('interviews.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                    {{-- <a href="{{ route('interviews.edit', $interview->id) }}"
                        class="inline-flex justify-center items-center rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:bg-amber-600 transition-all transform hover:-translate-y-0.5">
                        Ubah Jadwal
                    </a> --}}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
