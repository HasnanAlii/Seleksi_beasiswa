<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                Detail Pendaftaran
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('applications.index') }}" class="hover:text-blue-600 cursor-pointer transition">Pendaftaran</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">

                {{-- Header Banner --}}
                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-8 py-10 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-32 h-32 bg-white opacity-10 rounded-full blur-2xl"></div>

                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div class="flex-1">
                            <h3 class="text-3xl font-extrabold tracking-tight">{{ $application->student->name }}</h3>
                            <p class="text-blue-100 mt-1 font-semibold text-lg">{{ $application->scholarship->scholarship_name }}</p>
                        </div>

                        <div class="flex flex-col items-end">
                            @if($application->status == 'menunggu')
                                <span class="inline-flex items-center gap-2 bg-white text-amber-600 text-sm font-black px-6 py-3 rounded-2xl shadow-xl shadow-black/10">
                                    <div class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></div>
                                    MENUNGGU
                                </span>
                            @elseif($application->status == 'diproses')
                                <span class="inline-flex items-center gap-2 bg-white text-blue-600 text-sm font-black px-6 py-3 rounded-2xl shadow-xl shadow-black/10">
                                    <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                                    DIPROSES
                                </span>
                            @elseif($application->status == 'diterima')
                                <span class="inline-flex items-center gap-2 bg-white text-emerald-600 text-sm font-black px-6 py-3 rounded-2xl shadow-xl shadow-black/10">
                                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                    DITERIMA
                                </span>
                            @else
                                <span class="inline-flex items-center gap-2 bg-white text-rose-600 text-sm font-black px-6 py-3 rounded-2xl shadow-xl shadow-black/10">
                                    <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                                    DITOLAK
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Content --}}
                <div class="p-8 md:p-10 space-y-8">

                    {{-- Data Mahasiswa --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Informasi Mahasiswa</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Nama Mahasiswa</div>
                                <div class="text-base font-bold text-slate-800">{{ $application->student->name }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Nomor Pokok Mahasiswa (NPM)</div>
                                <div class="text-base font-bold text-slate-800">{{ $application->student->student_number }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Program Studi</div>
                                <div class="text-base font-bold text-slate-800">{{ $application->student->study_program }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Data Pendaftaran --}}
                    <div>
                        <h4 class="text-sm font-bold text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Detail Pendaftaran</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Beasiswa</div>
                                <div class="text-base font-bold text-slate-800">{{ $application->scholarship->scholarship_name }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-semibold text-slate-400 mb-1">Tanggal Mendaftar</div>
                                <div class="text-base font-bold text-slate-800">{{ $application->created_at->translatedFormat('d F Y H:i') }}</div>
                            </div>
                            @if($application->selection)
                                <div>
                                    <div class="text-xs font-semibold text-slate-400 mb-1">Tahap Seleksi Saat Ini</div>
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-700 rounded-lg text-sm font-bold border border-blue-100">
                                        {{-- <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div> --}}
                                        {{ $application->selection->stage }}
                                    </div>
                                </div>
                            @endif
                            <div class="{{ $application->selection ? 'md:col-span-1' : 'md:col-span-2' }}">
                                <div class="text-xs font-semibold text-slate-400 mb-1">Catatan / Deskripsi</div>
                                <div class="text-base font-medium text-slate-700 bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    {{ $application->description ?: 'Tidak ada catatan.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Perbandingan Persyaratan --}}
                    <div>
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Perbandingan Persyaratan</h4>
                        <div class="bg-slate-50/50 rounded-2xl border border-slate-100 overflow-hidden">
                            @php
                                $studentValues = $application->requirementValues->keyBy('requirement_id');
                            @endphp
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-100/50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Kriteria</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Standar Beasiswa</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Data Pendaftar</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Dokumen</th>
                                        <th class="px-6 py-3 text-left text-[10px] font-black text-slate-500 uppercase tracking-wider">Validasi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-transparent">
                                    @forelse($application->scholarship->requirements as $scholarReq)
                                        @php
                                            $val = $studentValues->get($scholarReq->requirement_id);
                                        @endphp
                                        <tr class="hover:bg-slate-50/70 transition-colors">
                                            <td class="px-6 py-4 text-sm font-bold text-slate-700">
                                                {{ $scholarReq->requirement->requirement_name }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-500 italic">
                                                {{ $scholarReq->terms ?: 'Tidak ada standar khusus' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                @if($val && $val->applicant_value)
                                                    <span class="font-black text-blue-600">{{ $val->applicant_value }}</span>
                                                @else
                                                    <span class="text-rose-400 text-xs italic">Data belum diisi</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                @if($val && ($val->documents->isNotEmpty() || $val->document_path))
                                                    <div class="flex flex-col gap-1.5">
                                                        @if($val->documents->isNotEmpty())
                                                            @foreach($val->documents as $doc)
                                                                <a href="{{ Storage::url($doc->document_path) }}"
                                                                    target="_blank"
                                                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-100 text-blue-700 hover:bg-blue-100 hover:border-blue-200 transition-all font-semibold text-xs group"
                                                                    title="{{ $doc->original_name ?: basename($doc->document_path) }}">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                    <span class="max-w-[120px] truncate">{{ $doc->original_name ?: basename($doc->document_path) }}</span>
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0 opacity-50 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                    </svg>
                                                                </a>
                                                            @endforeach
                                                        @elseif($val->document_path)
                                                            <a href="{{ Storage::url($val->document_path) }}"
                                                                target="_blank"
                                                                class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-blue-50 border border-blue-100 text-blue-700 hover:bg-blue-100 hover:border-blue-200 transition-all font-semibold text-xs group"
                                                                title="{{ basename($val->document_path) }}">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                                <span class="max-w-[120px] truncate">{{ basename($val->document_path) }}</span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 shrink-0 opacity-50 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                </svg>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="text-slate-300 text-xs italic">Tidak ada dokumen</span>
                                                @endif
                                            </td>
                                            {{-- Kolom Validasi --}}
                                            <td class="px-6 py-4 text-sm">
                                                @if(!$val)
                                                    <span class="text-slate-300 text-xs">—</span>
                                                @elseif($val->validation_status === 1)
                                                    <div class="space-y-1">
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-black">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                            </svg>
                                                            Valid
                                                        </span>
                                                        @if($val->validation_notes)
                                                            <p class="text-[11px] text-slate-500 italic leading-snug max-w-[160px]">{{ $val->validation_notes }}</p>
                                                        @endif
                                                        @if($val->validated_at)
                                                            <p class="text-[10px] text-slate-300">{{ $val->validated_at->translatedFormat('d M Y') }}</p>
                                                        @endif
                                                    </div>
                                                @elseif($val->validation_status === 2)
                                                    <div class="space-y-1">
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-rose-50 border border-rose-200 text-rose-700 text-[11px] font-black">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                            </svg>
                                                            Ditolak
                                                        </span>
                                                        @if($val->validation_notes)
                                                            <p class="text-[11px] text-slate-500 italic leading-snug max-w-[160px]">{{ $val->validation_notes }}</p>
                                                        @endif
                                                        @if($val->validated_at)
                                                            <p class="text-[10px] text-slate-300">{{ $val->validated_at->translatedFormat('d M Y') }}</p>
                                                        @endif
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-amber-50 border border-amber-200 text-amber-700 text-[11px] font-black">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                        </svg>
                                                        Menunggu
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-8 text-center text-sm text-slate-400 italic">Beasiswa ini tidak memiliki kriteria khusus.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Hasil Penilaian Wawancara --}}
                    @if($application->interviews->isNotEmpty())
                    <div>
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-4 border-b border-slate-100 pb-2">Hasil Penilaian Wawancara</h4>
                        <div class="space-y-4">
                            @foreach($application->interviews as $interview)
                                <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
                                    <div class="flex flex-col md:flex-row justify-between gap-4">
                                        <div class="space-y-1">
                                            <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Jadwal Wawancara</div>
                                            <div class="text-sm font-bold text-slate-700">{{ $interview->schedule->translatedFormat('l, d F Y - H:i') }} WIB</div>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            @foreach($interview->assessments as $assessment)
                                                <div class="text-right">
                                                    <div class="text-[10px] font-black uppercase tracking-wider text-slate-400">Nilai Akhir</div>
                                                    <div class="text-2xl font-black text-blue-600">{{ number_format($assessment->score, $assessment->score == floor($assessment->score) ? 0 : 2) }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    
                                    @foreach($interview->assessments as $assessment)
                                        <div class="mt-6 pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div>
                                                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Penilai / Interviewer</div>
                                                <div class="text-sm font-bold text-slate-700">{{ $assessment->interviewer }}</div>
                                            </div>
                                            <div>
                                                <div class="text-[10px] font-black uppercase tracking-wider text-slate-400 mb-1">Catatan Penilai</div>
                                                <div class="text-sm text-slate-600 italic">"{{ $assessment->notes ?: 'Tidak ada catatan khusus.' }}"</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Action Footer --}}
                <div class="bg-slate-50 px-8 py-6 border-t border-slate-100 flex justify-between items-center">
                    <a
                    @hasrole('admin|staf')
                    href="{{ route('applications.index') }}"
                    @endhasrole
                    @hasrole('mahasiswa')
                    href="{{ url()->previous() }}"
                    @endhasrole
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                        </svg>
                        Kembali
                    </a>
                    @hasrole('admin|staf')
                    <div class="flex gap-3">
                        <a href="{{ route('applications.edit', $application->id) }}"
                            class="inline-flex justify-center items-center rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-semibold text-white shadow-lg shadow-amber-500/30 hover:bg-amber-600 hover:shadow-amber-500/40 transition-all transform hover:-translate-y-0.5">
                           Validasi
                        </a>
                    </div>
                    @endhasrole
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
