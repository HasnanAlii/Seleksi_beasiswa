<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                Dashboard
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <span class="text-blue-600">Dashboard</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-10 bg-[#f0f6ff] min-h-screen px-6 lg:px-10">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- Hero Banner --}}
            <div class="rounded-3xl overflow-hidden bg-gradient-to-r from-blue-600 via-sky-600 to-cyan-500 shadow-2xl shadow-blue-200/60">
                <div class="px-8 py-8 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <p class="text-xs uppercase tracking-[0.25em] font-bold text-blue-100">Selamat datang</p>
                        <h1 class="mt-1 text-2xl sm:text-3xl font-black leading-tight">Sistem Seleksi Beasiswa</h1>
                        <p class="mt-2 text-blue-100 text-sm max-w-xl">Pantau dan kelola seluruh proses seleksi beasiswa secara real-time dalam satu tampilan.</p>
                    </div>
                    <div class="flex flex-wrap gap-3 shrink-0">
                        <a href="{{ route('scholarships.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/20 hover:bg-white/30 text-white text-sm font-bold rounded-xl border border-white/30 transition backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            Kelola Beasiswa
                        </a>
                        <a href="{{ route('applications.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white text-blue-700 text-sm font-bold rounded-xl shadow hover:shadow-md transition">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Lihat Pengajuan
                        </a>
                    </div>
                </div>
            </div>

            {{-- Stat Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $statCards = [
                        ['label' => 'Total Beasiswa', 'value' => $stats['total_scholarships'], 'color' => 'blue', 'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'route' => 'scholarships.index'],
                        ['label' => 'Total Mahasiswa', 'value' => $stats['total_students'], 'color' => 'violet', 'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'route' => 'students.index'],
                        ['label' => 'Total Pengajuan', 'value' => $stats['total_applications'], 'color' => 'amber', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'route' => 'applications.index'],
                        ['label' => 'Wawancara Belum Dinilai', 'value' => $stats['unassessed_interviews'], 'color' => 'rose', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'route' => 'interviews.index'],
                    ];
                    $colorMap = [
                        'blue'   => ['bg' => 'bg-blue-50', 'icon' => 'bg-blue-100 text-blue-600', 'text' => 'text-blue-600', 'border' => 'border-blue-100'],
                        'violet' => ['bg' => 'bg-violet-50', 'icon' => 'bg-violet-100 text-violet-600', 'text' => 'text-violet-600', 'border' => 'border-violet-100'],
                        'amber'  => ['bg' => 'bg-amber-50', 'icon' => 'bg-amber-100 text-amber-600', 'text' => 'text-amber-600', 'border' => 'border-amber-100'],
                        'rose'   => ['bg' => 'bg-rose-50', 'icon' => 'bg-rose-100 text-rose-600', 'text' => 'text-rose-600', 'border' => 'border-rose-100'],
                    ];
                @endphp
                @foreach($statCards as $card)
                @php $c = $colorMap[$card['color']]; @endphp
                <a href="{{ route($card['route']) }}" class="group rounded-2xl border {{ $c['border'] }} {{ $c['bg'] }} p-5 shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200">
                    <div class="flex items-center justify-between">
                        <div class="{{ $c['icon'] }} p-2.5 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}" />
                            </svg>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-300 group-hover:text-slate-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                    <div class="mt-4">
                        <p class="{{ $c['text'] }} text-3xl font-black">{{ $card['value'] }}</p>
                        <p class="text-slate-600 text-sm font-semibold mt-0.5">{{ $card['label'] }}</p>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Status Pengajuan & Recent --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- Status Donut --}}
                <div class="xl:col-span-1 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/60 p-6 flex flex-col">
                    <h3 class="text-lg font-bold text-slate-800">Status Pengajuan</h3>
                    <p class="text-sm text-slate-500 mt-0.5 mb-5">Distribusi status saat ini</p>

                    @php
                        $totalApps = $stats['total_applications'];
                        $statusItems = [
                            ['label' => 'Menunggu',  'value' => $stats['pending'],   'color' => 'bg-amber-400',  'text' => 'text-amber-600',  'bar' => 'bg-amber-400'],
                            ['label' => 'Diproses',  'value' => $stats['processed'], 'color' => 'bg-blue-400',   'text' => 'text-blue-600',   'bar' => 'bg-blue-400'],
                            ['label' => 'Diterima',  'value' => $stats['accepted'],  'color' => 'bg-emerald-400','text' => 'text-emerald-600','bar' => 'bg-emerald-400'],
                            ['label' => 'Ditolak',   'value' => $stats['rejected'],  'color' => 'bg-rose-400',   'text' => 'text-rose-600',   'bar' => 'bg-rose-400'],
                        ];
                    @endphp

                    <div class="space-y-3 flex-1">
                        @foreach($statusItems as $s)
                        @php $pct = $totalApps > 0 ? round($s['value'] / $totalApps * 100) : 0; @endphp
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <span class="text-sm font-semibold text-slate-700">{{ $s['label'] }}</span>
                                <span class="text-sm font-bold {{ $s['text'] }}">{{ $s['value'] }} <span class="text-slate-400 font-normal">({{ $pct }}%)</span></span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="{{ $s['bar'] }} h-2 rounded-full transition-all duration-700" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('applications.index') }}" class="mt-6 w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-700 text-sm font-bold rounded-xl border border-slate-200 transition">
                        Lihat Semua Pengajuan
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </a>
                </div>

                {{-- Recent Applications --}}
                <div class="xl:col-span-2 bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/60 p-6 flex flex-col">
                    <div class="flex justify-between items-center mb-5">
                        <div>
                            <h3 class="text-lg font-bold text-slate-800">Pengajuan Terbaru</h3>
                            <p class="text-sm text-slate-500 mt-0.5">5 pengajuan paling baru</p>
                        </div>
                        <a href="{{ route('applications.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-semibold hover:underline">Lihat semua →</a>
                    </div>

                    <div class="flex-1 space-y-3">
                        @forelse($recentApplications as $app)
                        @php
                            $badgeClass = match($app->status) {
                                'diterima' => 'bg-emerald-100 text-emerald-700',
                                'ditolak'  => 'bg-rose-100 text-rose-700',
                                'diproses' => 'bg-blue-100 text-blue-700',
                                default    => 'bg-amber-100 text-amber-700',
                            };
                            $statusLabel = match($app->status) {
                                'diterima' => 'Diterima',
                                'ditolak'  => 'Ditolak',
                                'diproses' => 'Diproses',
                                default    => 'Menunggu',
                            };
                        @endphp
                        <div class="flex items-center gap-4 p-3 rounded-xl border border-slate-100 hover:bg-slate-50/60 transition-colors">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-400 to-violet-500 flex items-center justify-center text-white text-sm font-black shrink-0">
                                {{ strtoupper(substr($app->student->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800 truncate">{{ $app->student->name ?? '-' }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ $app->scholarship->scholarship_name ?? '-' }}</p>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }} shrink-0">
                                <div class="w-2 h-2 rounded-full {{ match($app->status) { 'diterima' => 'bg-emerald-500', 'ditolak' => 'bg-rose-500', 'diproses' => 'bg-blue-500', default => 'bg-amber-500' } }}"></div>
                                {{ $statusLabel }}
                            </span>
                        </div>
                        @empty
                        <div class="flex flex-col items-center justify-center py-10 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <p class="text-sm font-medium">Belum ada pengajuan</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Wawancara Belum Dinilai --}}
            @if($pendingInterviews->count() > 0)
            <div class="bg-white rounded-3xl border border-amber-100 shadow-xl shadow-amber-100/30 p-6">
                <div class="flex justify-between items-center mb-5">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-400 text-white text-xs font-black">{{ $stats['unassessed_interviews'] }}</span>
                            Wawancara Belum Dinilai
                        </h3>
                        <p class="text-sm text-slate-500 mt-0.5">Segera lakukan penilaian</p>
                    </div>
                    <a href="{{ route('interviews.index') }}" class="text-sm text-amber-600 hover:text-amber-800 font-semibold hover:underline">Lihat semua →</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b border-slate-100">
                                <th class="pb-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Mahasiswa</th>
                                <th class="pb-3 text-left text-xs font-bold uppercase tracking-wider text-slate-400">Beasiswa</th>
                                <th class="pb-3 text-center text-xs font-bold uppercase tracking-wider text-slate-400">Jadwal</th>
                                <th class="pb-3 text-center text-xs font-bold uppercase tracking-wider text-slate-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($pendingInterviews as $interview)
                            <tr class="hover:bg-amber-50/40 transition-colors">
                                <td class="py-3 pr-4">
                                    <p class="text-sm font-bold text-slate-800">{{ $interview->application->student->name ?? '-' }}</p>
                                    <p class="text-xs text-slate-500">{{ $interview->application->student->student_number ?? '' }}</p>
                                </td>
                                <td class="py-3 pr-4">
                                    <p class="text-sm font-semibold text-slate-700">{{ $interview->application->scholarship->scholarship_name ?? '-' }}</p>
                                </td>
                                <td class="py-3 text-center">
                                    <p class="text-sm font-bold text-slate-800">{{ $interview->schedule->translatedFormat('d M Y') }}</p>
                                    <p class="text-xs text-slate-500">{{ $interview->schedule->format('H:i') }} WIB</p>
                                </td>
                                <td class="py-3 text-center">
                                    <a href="{{ route('interview-assessments.create', ['interview_id' => $interview->id]) }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold rounded-lg shadow-sm shadow-amber-200 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                        Nilai
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Quick Navigation --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/60 p-6">
                <h3 class="text-lg font-bold text-slate-800 mb-1">Navigasi Cepat</h3>
                <p class="text-sm text-slate-500 mb-5">Akses semua modul sistem</p>
                @php
                    $modules = [
                        ['label' => 'Berita',                        'route' => 'news.index',                   'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z', 'color' => 'slate'],
                        ['label' => 'Beasiswa',                      'route' => 'scholarships.index',           'icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'color' => 'blue'],
                        ['label' => 'Mahasiswa',                     'route' => 'students.index',               'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'violet'],
                        ['label' => 'Pengajuan',                     'route' => 'applications.index',           'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'amber'],
                        ['label' => 'Persyaratan',                   'route' => 'requirements.index',           'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'color' => 'teal'],
                        ['label' => 'Relasi Persyaratan',            'route' => 'scholarship-requirements.index','icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1', 'color' => 'cyan'],
                        ['label' => 'Seleksi',                       'route' => 'selections.index',             'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'color' => 'indigo'],
                        ['label' => 'Pengumuman',                    'route' => 'announcements.index',          'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z', 'color' => 'pink'],
                        ['label' => 'Kriteria Fuzzy',                'route' => 'fuzzy-criteria.index',        'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01', 'color' => 'purple'],
                        ['label' => 'Keanggotaan Fuzzy',             'route' => 'fuzzy-memberships.index',     'icon' => 'M4.871 4A17.926 17.926 0 003 12c0 2.874.673 5.59 1.871 8m14.13 0a17.926 17.926 0 001.87-8 17.926 17.926 0 00-1.87-8M9 9h1.246a1 1 0 01.961.725l1.586 5.55a1 1 0 00.961.725H15m1-7h-.08a2 2 0 00-1.519.698L9.6 15.302A2 2 0 018.08 16H8', 'color' => 'fuchsia'],
                        ['label' => 'Wawancara',                     'route' => 'interviews.index',             'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'orange'],
                        ['label' => 'Penilaian Wawancara',           'route' => 'interview-assessments.index', 'icon' => 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'color' => 'yellow'],
                        ['label' => 'Media Berita',                  'route' => 'news-media.index',             'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'green'],
                        ['label' => 'Dokumen Pengajuan',             'route' => 'application-documents.index', 'icon' => 'M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13', 'color' => 'red'],
                    ];
                    $navColors = [
                        'slate'   => 'bg-slate-100 text-slate-600 group-hover:bg-slate-200',
                        'blue'    => 'bg-blue-100 text-blue-600 group-hover:bg-blue-200',
                        'violet'  => 'bg-violet-100 text-violet-600 group-hover:bg-violet-200',
                        'amber'   => 'bg-amber-100 text-amber-600 group-hover:bg-amber-200',
                        'teal'    => 'bg-teal-100 text-teal-600 group-hover:bg-teal-200',
                        'cyan'    => 'bg-cyan-100 text-cyan-600 group-hover:bg-cyan-200',
                        'indigo'  => 'bg-indigo-100 text-indigo-600 group-hover:bg-indigo-200',
                        'pink'    => 'bg-pink-100 text-pink-600 group-hover:bg-pink-200',
                        'purple'  => 'bg-purple-100 text-purple-600 group-hover:bg-purple-200',
                        'fuchsia' => 'bg-fuchsia-100 text-fuchsia-600 group-hover:bg-fuchsia-200',
                        'orange'  => 'bg-orange-100 text-orange-600 group-hover:bg-orange-200',
                        'yellow'  => 'bg-yellow-100 text-yellow-600 group-hover:bg-yellow-200',
                        'green'   => 'bg-green-100 text-green-600 group-hover:bg-green-200',
                        'red'     => 'bg-red-100 text-red-600 group-hover:bg-red-200',
                    ];
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3">
                    @foreach($modules as $mod)
                    @php $nc = $navColors[$mod['color']]; @endphp
                    <a href="{{ route($mod['route']) }}" class="group flex flex-col items-center gap-2 p-4 rounded-2xl border border-slate-100 hover:border-slate-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 bg-white">
                        <div class="{{ $nc }} p-2.5 rounded-xl transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $mod['icon'] }}" />
                            </svg>
                        </div>
                        <span class="text-xs font-bold text-slate-700 text-center leading-tight">{{ $mod['label'] }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
