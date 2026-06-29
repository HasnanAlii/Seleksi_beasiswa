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

    <div class="py-10 bg-[#f8fafc] min-h-screen px-6 lg:px-10 relative overflow-hidden">
        {{-- Decorative background elements --}}
        <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-blue-50/50 to-transparent -z-10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-100/30 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-1/2 -left-24 w-72 h-72 bg-indigo-100/20 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto space-y-8 relative">

            {{-- Hero Banner --}}
            <div
                class="relative rounded-[2.5rem] overflow-hidden bg-[#0f172a] shadow-2xl shadow-slate-900/20 border border-white/10">
                {{-- Abstract background pattern --}}
                <div class="absolute inset-0 opacity-20">
                    <div
                        class="absolute top-0 right-0 w-full h-full bg-[radial-gradient(circle_at_80%_20%,#3b82f6_0%,transparent_50%)]">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-full h-full bg-[radial-gradient(circle_at_20%_80%,#6366f1_0%,transparent_50%)]">
                    </div>
                </div>

                <div
                    class="relative px-10 py-12 text-white flex flex-col lg:flex-row justify-between items-center gap-8">
                    <div class="max-w-2xl text-center lg:text-left">
                    
                        <h1 class="text-3xl md:text-4xl font-black leading-tight tracking-tight mb-3">
                            Selamat Datang, <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-300">{{ Auth::user()->name }}</span>
                        </h1>
                        <p class="text-slate-400 text-sm md:text-base leading-relaxed">
                            Pantau kemajuan seleksi beasiswa dan kelola pendaftar dengan sistem manajemen berbasis data
                            yang cerdas dan efisien.
                        </p>
                    </div>    
                    @hasrole('admin|staf')
                    <div class="flex flex-col sm:flex-row gap-3 shrink-0 w-full lg:w-auto">
                        <a href="{{ route('scholarships.index') }}"
                            class="flex items-center justify-center gap-2 px-6 py-3.5 bg-blue-600 hover:bg-blue-500 text-white text-sm font-bold rounded-2xl shadow-lg shadow-blue-600/20 transition-all hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            </svg>
                            Manajemen Beasiswa
                        </a>
                        <a href="{{ route('applications.index') }}"
                            class="flex items-center justify-center gap-2 px-6 py-3.5 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-2xl border border-white/10 backdrop-blur-md transition-all hover:-translate-y-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                            </svg>
                            Daftar Pendaftar
                        </a>
                    </div>
                    @endhasrole
                </div>
            </div>
            @hasrole('admin|staf')
            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $statCards = [
                        [
                            'label' => 'Beasiswa Aktif',
                            'value' => $stats['total_scholarships'],
                            'color' => 'blue',
                            'icon' =>
                                'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
                            'route' => 'scholarships.index',
                        ],
                        [
                            'label' => 'Penerima Beasiswa',
                            'value' => $stats['accepted'],
                            'color' => 'emerald',
                            'icon' =>
                                'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                            'route' => 'selections.index',
                        ],
                        [
                            'label' => 'Total Pengajuan',
                            'value' => $stats['total_applications'],
                            'color' => 'sky',
                            'icon' =>
                                'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                            'route' => 'applications.index',
                        ],
                        [
                            'label' => 'Perlu Dinilai',
                            'value' => $stats['unassessed_interviews'],
                            'color' => 'rose',
                            'icon' =>
                                'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                            'route' => 'interviews.index',
                        ],
                    ];
                    $colorMap = [
                        'blue' => [
                            'bg' => 'bg-white',
                            'icon' => 'bg-blue-50 text-blue-600',
                            'text' => 'text-slate-900',
                            'border' => 'border-slate-200/60',
                            'hover' => 'hover:border-blue-200',
                        ],
                        'indigo' => [
                            'bg' => 'bg-white',
                            'icon' => 'bg-indigo-50 text-indigo-600',
                            'text' => 'text-slate-900',
                            'border' => 'border-slate-200/60',
                            'hover' => 'hover:border-indigo-200',
                        ],
                        'sky' => [
                            'bg' => 'bg-white',
                            'icon' => 'bg-sky-50 text-sky-600',
                            'text' => 'text-slate-900',
                            'border' => 'border-slate-200/60',
                            'hover' => 'hover:border-sky-200',
                        ],
                        'rose' => [
                            'bg' => 'bg-white',
                            'icon' => 'bg-rose-50 text-rose-600',
                            'text' => 'text-slate-900',
                            'border' => 'border-slate-200/60',
                            'hover' => 'hover:border-rose-200',
                        ],
                        'emerald' => [
                            'bg' => 'bg-white',
                            'icon' => 'bg-emerald-50 text-emerald-600',
                            'text' => 'text-slate-900',
                            'border' => 'border-slate-200/60',
                            'hover' => 'hover:border-emerald-200',
                        ],
                    ];
                @endphp
                @foreach ($statCards as $card)
                    @php $c = $colorMap[$card['color']]; @endphp
                    <a href="{{ route($card['route']) }}"
                        class="group relative flex flex-col bg-white rounded-[2rem] border {{ $c['border'] }} p-6 shadow-sm hover:shadow-xl {{ $c['hover'] }} transition-all duration-300 overflow-hidden">
                        <div
                            class="absolute -right-4 -bottom-4 opacity-[0.03] group-hover:scale-110 transition-transform duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="{{ $card['icon'] }}" />
                            </svg>
                        </div>

                        <div class="flex items-center gap-4 mb-4">
                            <div class="{{ $c['icon'] }} p-3 rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="{{ $card['icon'] }}" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-black text-slate-400 uppercase tracking-widest">
                                    {{ $card['label'] }}</p>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <h4 class="text-4xl font-black text-slate-800 tracking-tight">{{ $card['value'] }}</h4>
                        </div>
                        {{-- <div
                            class="mt-4 flex items-center text-[10px] font-bold text-blue-600 uppercase tracking-wider group-hover:translate-x-1 transition-transform">
                            Detail Analitik
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 ml-1" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div> --}}
                    </a>
                @endforeach
            </div>
            @endhasrole

            @hasrole('admin|staf')

            {{-- Status Pengajuan & Recent --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- Status Donut --}}
                <div
                    class="xl:col-span-1 bg-white rounded-[2.5rem] border border-slate-200/60 shadow-xl shadow-slate-200/40 p-8 flex flex-col">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="p-2 bg-indigo-50 rounded-xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Statistik Seleksi</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ringkasan Tahapan
                            </p>
                        </div>
                    </div>

                    @php
                        $totalApps = $stats['total_applications'];
                        $selectionItems = [
                            [
                                'label' => 'Belum Seleksi',
                                'value' => $stats['pending'],
                                'color' => 'bg-amber-400',
                                'text' => 'text-amber-600',
                                'bar' => 'bg-amber-400',
                                'light' => 'bg-amber-50',
                            ],
                            [
                                'label' => 'Tahap Wawancara',
                                'value' => $stats['total_interviews'],
                                'color' => 'bg-blue-400',
                                'text' => 'text-blue-600',
                                'bar' => 'bg-blue-400',
                                'light' => 'bg-blue-50',
                            ],
                            [
                                'label' => 'Lolos Seleksi',
                                'value' => $stats['accepted'],
                                'color' => 'bg-emerald-400',
                                'text' => 'text-emerald-600',
                                'bar' => 'bg-emerald-400',
                                'light' => 'bg-emerald-50',
                            ],
                            [
                                'label' => 'Tidak Lolos',
                                'value' => $stats['rejected'],
                                'color' => 'bg-rose-400',
                                'text' => 'text-rose-600',
                                'bar' => 'bg-rose-400',
                                'light' => 'bg-rose-50',
                            ],
                        ];
                    @endphp

                    <div class="space-y-5 flex-1">
                        @foreach ($selectionItems as $s)
                            @php $pct = $totalApps > 0 ? round($s['value'] / $totalApps * 100) : 0; @endphp
                            <div class="group">
                                <div class="flex justify-between items-center mb-2">
                                    <span
                                        class="text-xs font-bold text-slate-600 uppercase tracking-wider">{{ $s['label'] }}</span>
                                    <div class="flex items-baseline gap-1">
                                        <span
                                            class="text-sm font-black {{ $s['text'] }}">{{ $s['value'] }}</span>
                                        <span
                                            class="text-[10px] font-bold text-slate-400">({{ $pct }}%)</span>
                                    </div>
                                </div>
                                <div
                                    class="h-2.5 {{ $s['light'] }} rounded-full overflow-hidden border border-slate-100">
                                    <div class="{{ $s['bar'] }} h-full rounded-full transition-all duration-1000 ease-out group-hover:brightness-110 shadow-sm"
                                        style="width: {{ $pct }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <a href="{{ route('selections.index') }}"
                        class="mt-8 group w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold rounded-2xl transition-all shadow-lg shadow-indigo-600/10">
                        Kelola Hasil Seleksi
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7-7 7" />
                        </svg>
                    </a>
                </div>

                {{-- Recent Applications --}}
                <div
                    class="xl:col-span-2 bg-white rounded-[2.5rem] border border-slate-200/60 shadow-xl shadow-slate-200/40 p-8 flex flex-col">
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-indigo-50 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 tracking-tight">Pengajuan Terbaru</h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Update
                                    Terakhir</p>
                            </div>
                        </div>
                        <a href="{{ route('applications.index') }}"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 text-xs font-black rounded-xl transition-colors">
                            Lihat Semua
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>

                    <div class="flex-1 space-y-4">
                        @forelse($recentApplications as $app)
                            @php
                                $badgeClass = match ($app->status) {
                                    'diterima' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'ditolak' => 'bg-rose-50 text-rose-600 border-rose-100',
                                    'diproses' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    default => 'bg-amber-50 text-amber-600 border-amber-100',
                                };
                                $statusLabel = match ($app->status) {
                                    'diterima' => 'Diterima',
                                    'ditolak' => 'Ditolak',
                                    'diproses' => 'Diproses',
                                    default => 'Menunggu',
                                };
                            @endphp
                            <div
                                class="group flex items-center gap-5 p-4 rounded-2xl border border-slate-100 hover:border-blue-200 hover:bg-blue-50/30 transition-all duration-300">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-slate-600 text-lg font-black shrink-0 group-hover:from-blue-100 group-hover:to-indigo-100 group-hover:text-blue-600 transition-all">
                                    {{ strtoupper(substr($app->student->name ?? '?', 0, 1)) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-0.5">
                                        <p class="text-sm font-black text-slate-800 truncate">
                                            {{ $app->student->name ?? '-' }}</p>
                                        <span class="text-[10px] text-slate-300">•</span>
                                        <p class="text-[11px] font-bold text-slate-400">
                                            {{ $app->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-xs font-bold text-slate-500 truncate flex items-center gap-1.5">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-slate-400"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        {{ $app->scholarship->scholarship_name ?? '-' }}
                                    </p>
                                </div>
                                <span
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border {{ $badgeClass }} shrink-0">
                                    <div
                                        class="w-1.5 h-1.5 rounded-full {{ match ($app->status) {'diterima' => 'bg-emerald-500','ditolak' => 'bg-rose-500','diproses' => 'bg-blue-500',default => 'bg-amber-500'} }}">
                                    </div>
                                    {{ $statusLabel }}
                                </span>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center py-16 text-slate-300">
                                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mb-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <p class="text-sm font-bold uppercase tracking-widest">Tidak ada data</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endhasrole

            @hasrole('admin|staf|kaprodi')

            {{-- Wawancara Belum Dinilai --}}
            @if ($pendingInterviews->count() > 0)
                <div class="bg-white rounded-[2.5rem] border border-amber-200/60 shadow-xl shadow-amber-200/20 p-8">
                    <div class="flex justify-between items-center mb-8">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-amber-50 rounded-xl relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
                                </svg>
                                <span class="absolute -top-1.5 -right-1.5 flex h-4 w-4">
                                    <span
                                        class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                                    <span
                                        class="relative inline-flex rounded-full h-4 w-4 bg-amber-500 flex items-center justify-center text-[8px] font-black text-white">{{ $stats['unassessed_interviews'] }}</span>
                                </span>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800 tracking-tight">Wawancara Perlu Penilaian
                                </h3>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tindakan
                                    Diperlukan</p>
                            </div>
                        </div>
                        <a href="{{ route('interviews.index') }}"
                            class="text-xs font-black text-amber-600 hover:text-amber-700 uppercase tracking-widest">Kelola
                            Semua →</a>
                    </div>
                    <div class="overflow-x-auto rounded-2xl border border-slate-100">
                        <table class="min-w-full divide-y divide-slate-100">
                            <thead>
                                <tr class="bg-slate-50/50 text-left">
                                    <th
                                        class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                        Mahasiswa</th>
                                    <th
                                        class="px-6 py-4 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                        Beasiswa</th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                        Jadwal</th>
                                    <th
                                        class="px-6 py-4 text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                        Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach ($pendingInterviews as $interview)
                                    <tr class="hover:bg-amber-50/20 transition-colors">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 text-xs font-black">
                                                    {{ strtoupper(substr($interview->application->student->name ?? '?', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <p class="text-sm font-black text-slate-800">
                                                        {{ $interview->application->student->name ?? '-' }}</p>
                                                    <p class="text-[10px] font-bold text-slate-400">
                                                        {{ $interview->application->student->student_number ?? '' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span
                                                class="inline-flex px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[10px] font-black uppercase tracking-wider border border-slate-200">
                                                {{ $interview->application->scholarship->scholarship_name ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex flex-col items-center">
                                                <span
                                                    class="text-sm font-black text-slate-700">{{ $interview->schedule->translatedFormat('d M Y') }}</span>
                                                <span
                                                    class="text-[10px] font-bold text-amber-600">{{ $interview->schedule->format('H:i') }}
                                                    WIB</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('interview-assessments.create', ['interview_id' => $interview->id]) }}"
                                                class="inline-flex items-center gap-2 px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white text-[10px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-amber-500/20 transition-all hover:-translate-y-0.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2.5"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                                Beri Nilai
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            @endhasrole
            @hasrole('admin|staf')

            {{-- Quick Navigation --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-200/60 shadow-xl shadow-slate-200/40 p-8">
                <div class="flex items-center gap-3 mb-8">
                    <div class="p-2 bg-slate-50 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-600" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight">Navigasi Akses Cepat</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Semua Modul Sistem
                        </p>
                    </div>
                </div>

                @php
                    $modules = [
                        [
                            'label' => 'Berita',
                            'route' => 'news.index',
                            'icon' =>
                                'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z',
                            'color' => 'slate',
                        ],
                        [
                            'label' => 'Beasiswa',
                            'route' => 'scholarships.index',
                            'icon' =>
                                'M12 14l9-5-9-5-9 5 9 5z M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z',
                            'color' => 'blue',
                        ],
                        [
                            'label' => 'Pengajuan',
                            'route' => 'applications.index',
                            'icon' =>
                                'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
                            'color' => 'amber',
                        ],
                        [
                            'label' => 'Persyaratan',
                            'route' => 'requirements.index',
                            'icon' =>
                                'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4',
                            'color' => 'teal',
                        ],
                        [
                            'label' => 'Seleksi',
                            'route' => 'selections.index',
                            'icon' =>
                                'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
                            'color' => 'indigo',
                        ],
                        [
                            'label' => 'Pengumuman',
                            'route' => 'announcements.index',
                            'icon' =>
                                'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
                            'color' => 'pink',
                        ],
                        [
                            'label' => 'Kriteria Fuzzy',
                            'route' => 'fuzzy-criteria.index',
                            'icon' =>
                                'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01',
                            'color' => 'purple',
                        ],
                        [
                            'label' => 'Keanggotaan Fuzzy',
                            'route' => 'fuzzy-memberships.index',
                            'icon' =>
                                'M4.871 4A17.926 17.926 0 003 12c0 2.874.673 5.59 1.871 8m14.13 0a17.926 17.926 0 001.87-8 17.926 17.926 0 00-1.87-8M9 9h1.246a1 1 0 01.961.725l1.586 5.55a1 1 0 00.961.725H15m1-7h-.08a2 2 0 00-1.519.698L9.6 15.302A2 2 0 018.08 16H8',
                            'color' => 'fuchsia',
                        ],
                        [
                            'label' => 'Wawancara',
                            'route' => 'interviews.index',
                            'icon' =>
                                'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                            'color' => 'orange',
                        ],
                        [
                            'label' => 'Penilaian Wawancara',
                            'route' => 'interview-assessments.index',
                            'icon' =>
                                'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z',
                            'color' => 'yellow',
                        ],
                    ];
                    $navColors = [
                        'slate' => 'bg-slate-50 text-slate-500 group-hover:bg-slate-900 group-hover:text-white',
                        'blue' => 'bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white',
                        'violet' => 'bg-violet-50 text-violet-600 group-hover:bg-violet-600 group-hover:text-white',
                        'amber' => 'bg-amber-50 text-amber-600 group-hover:bg-amber-600 group-hover:text-white',
                        'teal' => 'bg-teal-50 text-teal-600 group-hover:bg-teal-600 group-hover:text-white',
                        'indigo' => 'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white',
                        'pink' => 'bg-pink-50 text-pink-600 group-hover:bg-pink-600 group-hover:text-white',
                        'purple' => 'bg-purple-50 text-purple-600 group-hover:bg-purple-600 group-hover:text-white',
                        'fuchsia' => 'bg-fuchsia-50 text-fuchsia-600 group-hover:bg-fuchsia-600 group-hover:text-white',
                        'orange' => 'bg-orange-50 text-orange-600 group-hover:bg-orange-600 group-hover:text-white',
                        'yellow' => 'bg-yellow-50 text-yellow-600 group-hover:bg-yellow-600 group-hover:text-white',
                    ];
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4">
                    @foreach ($modules as $mod)
                        @php $nc = $navColors[$mod['color']] ?? 'bg-slate-50 text-slate-500 group-hover:bg-slate-900 group-hover:text-white'; @endphp
                        <a href="{{ route($mod['route']) }}"
                            class="group flex items-center gap-4 p-4 rounded-2xl border border-slate-100 hover:border-blue-200 hover:shadow-lg hover:shadow-blue-900/5 transition-all duration-300 bg-white">
                            <div
                                class="{{ $nc }} w-12 h-12 shrink-0 rounded-xl flex items-center justify-center transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="{{ $mod['icon'] }}" />
                                </svg>
                            </div>
                            <span
                                class="text-xs font-black text-slate-700 leading-tight uppercase tracking-wider group-hover:text-blue-600 transition-colors">{{ $mod['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endhasrole

        </div>
    </div>
</x-app-layout>
