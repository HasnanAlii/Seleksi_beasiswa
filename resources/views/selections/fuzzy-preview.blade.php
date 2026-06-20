<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">Seleksi AI</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('selections.index') }}"
                    class="hover:text-blue-600 cursor-pointer transition">Seleksi</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Preview Kelayakan AI</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-[#f0f6ff] min-h-screen px-3 md:px-10">
        <div class="mx-auto sm:px-4 lg:px-8 space-y-8">

            {{-- Hero Banner --}}
            <div
                class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-violet-700 via-blue-700 to-indigo-900 p-8 lg:p-12 shadow-2xl shadow-blue-900/30">
                {{-- Decorative orbs --}}
                <div class="pointer-events-none absolute -top-16 -right-16 h-64 w-64 rounded-full bg-white/5 blur-2xl">
                </div>
                <div
                    class="pointer-events-none absolute -bottom-16 -left-10 h-48 w-48 rounded-full bg-violet-400/10 blur-2xl">
                </div>

                <div class="relative flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                    <div class="flex items-center gap-5">
                        {{-- AI Brain Icon --}}
                              <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl bg-white/10 ring-2 ring-white/20 backdrop-blur-sm shadow-inner">
                          <span class="text-4xl font-extrabold text-white">✦ </span>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                {{-- <span
                                    class="inline-flex items-center gap-1 rounded-full bg-violet-400/20 px-2.5 py-0.5 text-xs font-bold text-violet-200 ring-1 ring-violet-300/20">
                                    ✦ Fuzzy Tsukamoto
                                </span> --}}

                            </div>
                            <h3 class="text-2xl font-extrabold text-white tracking-tight">Preview Kelayakan Seleksi AI
                            </h3>
                            <p class="mt-1 text-sm text-blue-200 max-w-xl">
                                Rekomendasi kelayakan berdasarkan <strong class="text-white">Fuzzy Logic</strong> untuk
                                pendaftar berstatus <strong class="text-white">Siap di Proses</strong>. Tinjau hasilnya
                                lalu terapkan jika sesuai.
                            </p>
                        </div>
                    </div>
                    {{-- Summary badges --}}
                    <div class="flex flex-wrap gap-3 shrink-0">
                        <div
                            class="text-center rounded-2xl bg-white/10 px-6 py-3 ring-1 ring-white/15 backdrop-blur-sm">
                            <div class="text-2xl font-black text-emerald-300">{{ $layakCount }}</div>
                            <div class="text-xs font-semibold text-emerald-200">Layak</div>
                        </div>
                        <div
                            class="text-center rounded-2xl bg-white/10 px-6 py-3 ring-1 ring-white/15 backdrop-blur-sm">
                            <div class="text-2xl font-black text-rose-300">{{ $tidakLayakCount }}</div>
                            <div class="text-xs font-semibold text-rose-200">Tidak Layak</div>
                        </div>
                        <div
                            class="text-center rounded-2xl bg-white/10 px-6 py-3 ring-1 ring-white/15 backdrop-blur-sm">
                            <div class="text-2xl font-black text-white">{{ count($results) }}</div>
                            <div class="text-xs font-semibold text-blue-200">Total Dievaluasi</div>
                        </div>
                    </div>
                </div>
            </div>

            @if (count($results) === 0)
                {{-- Empty State --}}
                <div
                    class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 p-16 text-center">
                    <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-violet-50">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-violet-400" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Tidak Ada Data yang Siap Diproses</h3>
                    <p class="mt-2 text-sm text-slate-500 max-w-sm mx-auto">
                        Belum ada pendaftar dengan status seleksi <strong>"Siap di Proses"</strong> yang dapat
                        dievaluasi oleh sistem Fuzzy Logic.
                    </p>
                    <a href="{{ route('selections.index') }}"
                        class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-violet-600 text-white text-sm font-semibold rounded-xl hover:bg-violet-700 transition">
                        Kembali ke Seleksi
                    </a>
                </div>
            @else
                {{-- Results Table --}}
                <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Hasil Evaluasi Kelayakan –
                                    {{ count($results) }} Pendaftar</h3>
                                <p class="text-sm text-slate-500 mt-0.5">
                                    Metode <strong class="text-violet-600">Fuzzy Tsukamoto</strong>: defuzzifikasi
                                    rata-rata terbobot z = Σ(α·z) / Σ(α). 
                                    {{-- Nilai wawancara disertakan sebagai kriteria tambahan. Threshold layak ≥ 50. --}}
                                </p>
                            </div>
                        </div>

                        <div class="rounded-2xl border border-slate-200 overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-100">
                                <thead class="bg-slate-50/80">
                                    <tr>
                                        <th
                                            class="px-5 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            No</th>
                                        <th
                                            class="px-5 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Mahasiswa</th>
                                        <th
                                            class="px-5 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Beasiswa</th>
                                        <th
                                            class="px-5 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Skor Fuzzy</th>
                                        {{-- <th
                                            class="px-5 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Nilai Wawancara</th> --}}
                                        <th
                                            class="px-5 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Kelayakan</th>
                                        <th
                                            class="px-5 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                            Detail Kriteria</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 bg-white">
                                    @foreach ($results as $i => $result)
                                    {{-- Wrapper tbody per baris agar Alpine scope bisa dibagi antara baris utama dan baris detail --}}
                                    <tbody class="divide-y divide-slate-100 bg-white" x-data="{ open: false }">
                                        <tr class="group hover:bg-slate-50/70 transition-colors duration-150">
                                            <td class="px-5 py-4 text-center text-sm text-slate-400 font-medium">
                                                {{ $i + 1 }}</td>
                                            <td class="px-5 py-4">
                                                <div class="font-bold text-blue-600 text-sm">
                                                    {{ $result['student_name'] }}</div>
                                                <div class="text-xs text-slate-400">
                                                    {{ $result['student_number'] ?? '-' }}</div>
                                            </td>
                                            <td class="px-5 py-4 text-sm text-slate-700 font-semibold max-w-[200px]">
                                                {{ $result['scholarship_name'] }}
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                {{-- Score gauge --}}
                                                @php
                                                    $score = $result['fuzzy_score'];
                                                    $isLayak = $result['recommended_status'] === 'layak';
                                                    $barColor = $isLayak ? 'bg-emerald-500' : 'bg-rose-500';
                                                    $textColor = $isLayak ? 'text-emerald-600' : 'text-rose-600';
                                                @endphp
                                                <div class="inline-flex flex-col items-center gap-1.5">
                                                    <div
                                                        class="relative h-2 w-24 rounded-full bg-slate-100 overflow-hidden">
                                                        <div class="{{ $barColor }} h-full rounded-full transition-all duration-700"
                                                            style="width: {{ min($score, 100) }}%"></div>
                                                    </div>
                                                    <span class="text-xs font-black {{ $textColor }}">
                                                        {{ $result['fuzzy_score'] }}<span
                                                            class="text-slate-400 font-normal">/100</span>
                                                    </span>
                                                </div>
                                            </td>
                                            {{-- Nilai Wawancara column --}}
                                            {{-- <td class="px-5 py-4 text-center">
                                                @php
                                                    $iScore = $result['interview_score'] ?? 0;
                                                    $iColor = $iScore >= 60 ? 'text-sky-600' : ($iScore >= 40 ? 'text-amber-600' : 'text-slate-400');
                                                    $iBg = $iScore >= 60 ? 'bg-sky-50 border-sky-200' : ($iScore >= 40 ? 'bg-amber-50 border-amber-200' : 'bg-slate-50 border-slate-200');
                                                    $iBar = $iScore >= 60 ? 'bg-sky-500' : ($iScore >= 40 ? 'bg-amber-400' : 'bg-slate-300');
                                                @endphp
                                                @if ($iScore > 0)
                                                    <div class="inline-flex flex-col items-center gap-1.5">
                                                        <div class="relative h-2 w-20 rounded-full bg-slate-100 overflow-hidden">
                                                            <div class="{{ $iBar }} h-full rounded-full transition-all duration-700"
                                                                style="width: {{ min($iScore, 100) }}%"></div>
                                                        </div>
                                                        <span class="text-xs font-black {{ $iColor }}">
                                                            {{ $iScore }}<span class="text-slate-400 font-normal">/100</span>
                                                        </span>
                                                    </div>
                                                @else
                                                    <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-slate-400 bg-slate-50 border border-slate-200 rounded-lg px-2 py-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                                        </svg>
                                                        Belum Ada
                                                    </span>
                                                @endif
                                            </td> --}}
                                            <td class="px-5 py-4 text-center">
                                                @if ($result['recommended_status'] === 'layak')
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3.5 w-3.5 text-emerald-500" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Layak
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-black uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3.5 w-3.5 text-rose-500" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        Tidak Layak
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-4 text-center">
                                                @if (count($result['criteria_details']) > 0)
                                                    <button @click="open = !open"
                                                        class="inline-flex items-center gap-1 text-xs font-semibold text-violet-600 hover:text-violet-800 transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="h-3.5 w-3.5 transition-transform duration-200"
                                                            :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path fill-rule="evenodd"
                                                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                                clip-rule="evenodd" />
                                                        </svg>
                                                        {{ count($result['criteria_details']) }} kriteria
                                                    </button>
                                                @else
                                                    <span class="text-xs text-slate-400 italic">Fallback mode</span>
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- Expandable criteria detail row --}}
                                        @if (count($result['criteria_details']) > 0)
                                            <tr x-show="open"
                                                x-transition:enter="transition ease-out duration-200"
                                                x-transition:enter-start="opacity-0 -translate-y-1"
                                                x-transition:enter-end="opacity-100 translate-y-0"
                                                x-transition:leave="transition ease-in duration-100"
                                                x-transition:leave-start="opacity-100 translate-y-0"
                                                x-transition:leave-end="opacity-0 -translate-y-1"
                                                style="display:none;">
                                                <td colspan="7"
                                                    class="bg-violet-50/60 px-8 py-4 border-t border-violet-100">
                                                    {{-- Tsukamoto step summary --}}
                                                    {{-- <div class="mb-4 grid grid-cols-2 sm:grid-cols-4 gap-3">
                                                        <div
                                                            class="rounded-xl bg-white border border-violet-100 px-3 py-2 text-center shadow-sm">
                                                            <div
                                                                class="text-[10px] font-bold text-violet-400 uppercase tracking-wider mb-0.5">
                                                                α Layak</div>
                                                            <div class="text-sm font-black text-emerald-600">
                                                                {{ $result['alpha_layak'] }}</div>
                                                        </div>
                                                        <div
                                                            class="rounded-xl bg-white border border-violet-100 px-3 py-2 text-center shadow-sm">
                                                            <div
                                                                class="text-[10px] font-bold text-violet-400 uppercase tracking-wider mb-0.5">
                                                                z Layak</div>
                                                            <div class="text-sm font-black text-emerald-600">
                                                                {{ $result['z_layak'] }}</div>
                                                        </div>
                                                        <div
                                                            class="rounded-xl bg-white border border-rose-100 px-3 py-2 text-center shadow-sm">
                                                            <div
                                                                class="text-[10px] font-bold text-rose-400 uppercase tracking-wider mb-0.5">
                                                                α Tidak Layak</div>
                                                            <div class="text-sm font-black text-rose-600">
                                                                {{ $result['alpha_tidak'] }}</div>
                                                        </div>
                                                        <div
                                                            class="rounded-xl bg-white border border-rose-100 px-3 py-2 text-center shadow-sm">
                                                            <div
                                                                class="text-[10px] font-bold text-rose-400 uppercase tracking-wider mb-0.5">
                                                                z Tidak Layak</div>
                                                            <div class="text-sm font-black text-rose-600">
                                                                {{ $result['z_tidak'] }}</div>
                                                        </div>
                                                    </div> --}}

                                                    {{-- Interview score highlight --}}
                                                    {{-- @if (isset($result['interview_score']) && $result['interview_score'] > 0)
                                                        <div class="mb-4 flex items-center gap-3 rounded-xl bg-sky-50 border border-sky-200 px-4 py-3">
                                                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-sky-100">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-sky-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                                                    <circle cx="9" cy="7" r="4"/>
                                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <div class="text-[10px] font-bold text-sky-600 uppercase tracking-wider">Nilai Wawancara (Rata-rata)</div>
                                                                <div class="flex items-center gap-2 mt-1">
                                                                    <div class="h-2 flex-1 rounded-full bg-sky-100 overflow-hidden">
                                                                        <div class="h-full rounded-full bg-sky-500 transition-all duration-700"
                                                                            style="width: {{ min($result['interview_score'], 100) }}%"></div>
                                                                    </div>
                                                                    <span class="text-sm font-black text-sky-700">{{ $result['interview_score'] }}<span class="text-sky-400 font-normal text-xs">/100</span></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="mb-4 flex items-center gap-3 rounded-xl bg-slate-50 border border-slate-200 px-4 py-3">
                                                            <div class="text-[11px] text-slate-400">⚠️ <strong>Nilai Wawancara:</strong> Tidak tersedia — nilai default 0 digunakan dalam perhitungan (mempengaruhi skor fuzzy).</div>
                                                        </div>
                                                    @endif --}}

                                                    {{-- Defuzzification summary --}}
                                                    @php $wr = $result['winning_rule'] ?? null; @endphp
                                                    <div class="mb-4 grid grid-cols-2 gap-2 text-center">
                                                        <div class="rounded-xl bg-white border border-violet-100 px-3 py-2 shadow-sm">
                                                            <div class="text-[9px] font-bold text-violet-400 uppercase tracking-wider mb-0.5">Skor Fuzzy</div>
                                                            <div class="text-base font-black {{ $result['recommended_status']==='layak' ? 'text-emerald-600' : 'text-rose-600' }}">{{ $result['fuzzy_score'] }}</div>
                                                            {{-- <div class="text-[9px] text-slate-400">= Σ(α·z) / Σα</div> --}}
                                                        </div>
                                                        {{-- <div class="rounded-xl bg-indigo-50 border border-indigo-200 px-3 py-2 shadow-sm">
                                                            <div class="text-[9px] font-bold text-indigo-500 uppercase tracking-wider mb-0.5">Σ(α × z)</div>
                                                            <div class="text-base font-black text-indigo-600">{{ $result['sum_alpha_z'] ?? '-' }}</div>
                                                            <div class="text-[9px] text-slate-400">pembilang</div>
                                                        </div>
                                                        <div class="rounded-xl bg-slate-50 border border-slate-200 px-3 py-2 shadow-sm">
                                                            <div class="text-[9px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Σα</div>
                                                            <div class="text-base font-black text-slate-700">{{ $result['sum_alpha'] ?? '-' }}</div>
                                                            <div class="text-[9px] text-slate-400">penyebut</div>
                                                        </div> --}}
                                                        <div class="rounded-xl bg-{{ $result['recommended_status']==='layak' ? 'emerald' : 'rose' }}-50 border-2 border-{{ $result['recommended_status']==='layak' ? 'emerald' : 'rose' }}-300 px-3 py-2 shadow-sm">
                                                            <div class="text-[9px] font-bold text-{{ $result['recommended_status']==='layak' ? 'emerald' : 'rose' }}-400 uppercase tracking-wider mb-0.5">Keputusan</div>
                                                            <div class="text-sm font-black text-{{ $result['recommended_status']==='layak' ? 'emerald' : 'rose' }}-600 uppercase">{{ $result['recommended_status'] }}</div>
                                                            {{-- <div class="text-[9px] text-slate-400">{{ $result['fuzzy_score'] }} {{ $result['recommended_status']==='layak' ? '> 50' : '≤ 50' }}</div> --}}
                                                        </div>
                                                    </div>

                                                    {{-- Warning jika tidak ada rule yang aktif --}}
                                                    @if (empty($result['rule_results']))
                                                        <div class="mb-4 flex items-start gap-3 rounded-xl bg-amber-50 border border-amber-200 px-4 py-3 shadow-sm">
                                                            <span class="text-lg mt-0.5">⚠️</span>
                                                            <div>
                                                                <div class="text-xs font-bold text-amber-700 mb-0.5">Tidak Ada Rule Aktif (Kombinasi Belum Terdaftar)</div>
                                                                <div class="text-[11px] text-amber-600 leading-relaxed">
                                                                    Data pendaftar ini tidak cocok dengan satu pun dari <strong>15 aturan utama</strong> yang disimpan di sistem. Aturan kombinasi lain belum ditambahkan ke database. 
                                                                    Sistem menggunakan perhitungan cadangan (<em>fallback mode</em>) berdasarkan rata-rata derajat keanggotaan agar penilaian tetap dapat diproses.
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- Rule Inference Table --}}
                                                    @if (!empty($result['rule_results']))
                                                        <div class="text-xs font-bold text-violet-700 uppercase tracking-widest mb-2">Inferensi – Rules Aktif (α > 0)</div>
                                                        @php
                                                            // Urutkan berdasarkan α tertinggi
                                                            $sortedRules = collect($result['rule_results'])
                                                                ->sortByDesc('alpha')
                                                                ->values()
                                                                ->all();
                                                            $limitShow  = 10;
                                                            $totalRules = count($sortedRules);
                                                        @endphp
                                                        <div x-data="{ showAll: false }" class="rounded-xl border border-violet-100 overflow-hidden mb-4">
                                                            <table class="min-w-full divide-y divide-violet-50 text-[10px]">
                                                                <thead class="bg-violet-50">
                                                                    <tr>
                                                                        <th class="px-3 py-2 text-left font-bold text-violet-500 uppercase">Rule</th>
                                                                        <th class="px-3 py-2 text-center font-bold text-violet-500 uppercase">α (firing)</th>
                                                                        <th class="px-3 py-2 text-center font-bold text-violet-500 uppercase">z (output)</th>
                                                                        <th class="px-3 py-2 text-center font-bold text-violet-500 uppercase">Output</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="bg-white divide-y divide-violet-50">
                                                                    @foreach ($sortedRules as $i => $rr)
                                                                        @php $isWinner = $wr && $wr['rule'] === $rr['rule']; @endphp
                                                                        <tr
                                                                            x-show="showAll || {{ $i }} < {{ $limitShow }} || {{ $isWinner ? 'true' : 'false' }}"
                                                                            class="{{ $isWinner ? 'ring-2 ring-inset ring-violet-400 bg-violet-50' : ($rr['output']==='layak' ? 'bg-emerald-50/40' : 'bg-rose-50/40') }}">
                                                                            <td class="px-3 py-1.5 font-bold text-slate-600">
                                                                                @if($isWinner)<span class="mr-1 text-violet-500">★</span>@endif
                                                                                {{ $rr['rule'] }}
                                                                            </td>
                                                                            <td class="px-3 py-1.5 text-center font-black text-indigo-600">{{ $rr['alpha'] }}</td>
                                                                            <td class="px-3 py-1.5 text-center font-black {{ $isWinner ? 'text-violet-700' : 'text-slate-700' }}">{{ $rr['z'] }}</td>
                                                                            <td class="px-3 py-1.5 text-center">
                                                                                <span class="inline-block rounded-full px-2 py-0.5 text-[9px] font-black uppercase {{ $rr['output']==='layak' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">{{ $rr['output'] }}</span>
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                            @if ($totalRules > $limitShow)
                                                                <div class="px-4 py-2 bg-violet-50/60 border-t border-violet-100 flex items-center justify-between">
                                                                    <span class="text-[10px] text-violet-500">
                                                                        Menampilkan <span x-text="showAll ? '{{ $totalRules }}' : '{{ min($limitShow, $totalRules) }}'"></span> dari {{ $totalRules }} rules aktif
                                                                    </span>
                                                                    <button @click="showAll = !showAll"
                                                                        class="text-[10px] font-bold text-violet-600 hover:text-violet-800 transition-colors">
                                                                        <span x-text="showAll ? '▲ Sembunyikan' : '▼ Tampilkan semua ({{ $totalRules }})'"></span>
                                                                    </button>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif



                                                    <div class="text-xs font-bold text-violet-700 uppercase tracking-widest mb-3">
                                                        Fuzzifikasi – Derajat Keanggotaan per Kriteria</div>
                                                    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                                                        @foreach ($result['criteria_details'] as $detail)
                                                            @php
                                                                $isInv = !empty($detail['is_inverse']);
                                                                $isIv  = !empty($detail['is_interview']);
                                                                if ($isIv) {
                                                                    $cardBg     = 'bg-sky-50 border-sky-200';
                                                                    $nameColor  = 'text-sky-700';
                                                                    $badgeCls   = 'bg-sky-200 text-sky-700';
                                                                    // $badgeLbl   = '↑ Wawancara';
                                                                    $dirLbl     = '↑ besar = baik';
                                                                } elseif ($isInv) {
                                                                    $cardBg     = 'bg-amber-50 border-amber-200';
                                                                    $nameColor  = 'text-amber-700';
                                                                    $badgeCls   = 'bg-amber-200 text-amber-700';
                                                                    // $badgeLbl   = '↓ Inverse';
                                                                    $dirLbl     = '↓ kecil = baik';
                                                                } else {
                                                                    $cardBg     = 'bg-white border-violet-100';
                                                                    $nameColor  = 'text-violet-700';
                                                                    $badgeCls   = 'bg-emerald-100 text-emerald-700';
                                                                    // $badgeLbl   = '↑ Increasing';
                                                                    $dirLbl     = '↑ besar = baik';
                                                                }
                                                                $setLow  = $detail['set_low']  ?? '-';
                                                                $setMid  = $detail['set_mid']  ?? '-';
                                                                $setHigh = $detail['set_high'] ?? '-';
                                                                $muLow   = $detail['mu_low']   ?? 0;
                                                                $muMid   = $detail['mu_mid']   ?? 0;
                                                                $muHigh  = $detail['mu_high']  ?? 0;
                                                            @endphp
                                                            <div class="rounded-xl {{ $cardBg }} border px-4 py-3 shadow-sm">
                                                                <div class="flex items-center gap-1.5 mb-2">
                                                                    <div class="text-xs font-bold {{ $nameColor }} flex-1 leading-tight">{{ $detail['criteria_name'] }}</div>
                                                                    {{-- <span class="text-[9px] font-bold {{ $badgeCls }} rounded-full px-1.5 py-0.5 uppercase tracking-wider shrink-0">{{ $badgeLbl }}</span> --}}
                                                                </div>
                                                                <div class="flex flex-wrap gap-x-2 gap-y-0.5 text-xs text-slate-500 mb-3">
                                                                    <span>Nilai: <strong class="text-slate-800">{{ $detail['applicant_value'] }}</strong></span>
                                                                    <span class="italic">{{ $dirLbl }}</span>
                                                                </div>
                                                                {{-- 3 himpunan --}}
                                                                <div class="space-y-1.5 mb-2">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="w-20 shrink-0 text-[10px] font-semibold text-slate-500 truncate">{{ $setLow }}</span>
                                                                        <div class="h-1.5 flex-1 rounded-full bg-slate-100 overflow-hidden">
                                                                            <div class="h-full bg-slate-400 rounded-full" style="width:{{ min($muLow*100,100) }}%"></div>
                                                                        </div>
                                                                        <span class="w-7 text-right text-[10px] font-black text-slate-500">{{ $muLow }}</span>
                                                                    </div>
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="w-20 shrink-0 text-[10px] font-semibold text-indigo-500 truncate">{{ $setMid }}</span>
                                                                        <div class="h-1.5 flex-1 rounded-full bg-indigo-100 overflow-hidden">
                                                                            <div class="h-full bg-indigo-400 rounded-full" style="width:{{ min($muMid*100,100) }}%"></div>
                                                                        </div>
                                                                        <span class="w-7 text-right text-[10px] font-black text-indigo-500">{{ $muMid }}</span>
                                                                    </div>
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="w-20 shrink-0 text-[10px] font-semibold text-emerald-600 truncate">{{ $setHigh }}</span>
                                                                        <div class="h-1.5 flex-1 rounded-full bg-emerald-100 overflow-hidden">
                                                                            <div class="h-full bg-emerald-500 rounded-full" style="width:{{ min($muHigh*100,100) }}%"></div>
                                                                        </div>
                                                                        <span class="w-7 text-right text-[10px] font-black text-emerald-600">{{ $muHigh }}</span>
                                                                    </div>
                                                                </div>
                                                                {{-- <div class="border-t border-dashed border-slate-200 my-2"></div> --}}
                                                                {{-- μ_good & μ_bad --}}
                                                                {{-- <div class="space-y-1">
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="w-20 shrink-0 text-[10px] font-bold text-emerald-600">μ_good</span>
                                                                        <div class="h-1.5 flex-1 rounded-full bg-emerald-100 overflow-hidden">
                                                                            <div class="h-full bg-emerald-500 rounded-full" style="width:{{ min($detail['mu_good']*100,100) }}%"></div>
                                                                        </div>
                                                                        <span class="w-7 text-right text-[10px] font-black text-emerald-600">{{ $detail['mu_good'] }}</span>
                                                                    </div>
                                                                    <div class="flex items-center gap-2">
                                                                        <span class="w-20 shrink-0 text-[10px] font-bold text-rose-500">μ_bad</span>
                                                                        <div class="h-1.5 flex-1 rounded-full bg-rose-100 overflow-hidden">
                                                                            <div class="h-full bg-rose-400 rounded-full" style="width:{{ min($detail['mu_bad']*100,100) }}%"></div>
                                                                        </div>
                                                                        <span class="w-7 text-right text-[10px] font-black text-rose-500">{{ $detail['mu_bad'] }}</span>
                                                                    </div>
                                                                </div> --}}
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- Action Footer --}}
                        <div
                            class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4 rounded-2xl bg-slate-50 border border-slate-200 px-6 py-4">
                            <div class="flex items-start gap-3">
                                {{-- <div class="mt-0.5 h-8 w-8 shrink-0 rounded-lg bg-amber-100 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-amber-600" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="text-sm text-slate-600">
                                    <span class="font-semibold text-slate-800">Perhatian:</span>
                                    Menerapkan hasil akan mengubah status seleksi: <strong class="text-emerald-700">Layak → Diterima</strong> dan <strong class="text-rose-700">Tidak Layak → Tidak Diterima</strong>.
                                </div> --}}
                            </div>
                            <div class="flex gap-3 shrink-0">
                                <a href="{{ route('selections.index') }}"
                                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Batal
                                </a>
                                <form action="{{ route('selections.apply-fuzzy') }}" method="POST"
                                    id="apply-fuzzy-form"
                                    data-confirm-title="Terapkan Hasil AI?"
                                    data-confirm-label="Ya, Terapkan"
                                    data-confirm-message="Yakin ingin menerapkan hasil kelayakan AI ini?">
                                    @csrf
                                    <button type="submit"
                                        class="group inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-600 to-blue-600 text-white text-sm font-bold rounded-xl shadow-lg shadow-violet-500/30 hover:from-violet-700 hover:to-blue-700 hover:shadow-violet-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                         <span class="text-xl font-extrabold text-white">✦ </span>
                                        Terapkan Hasil Kelayakan AI
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

</x-app-layout>
