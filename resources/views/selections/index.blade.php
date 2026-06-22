<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">Data Seleksi</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Seleksi</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-[#f0f6ff] min-h-screen px-3 md:px-10">
        <div class="mx-auto sm:px-4 lg:px-8">
            <div class="space-y-8">
                <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100">
                    <div class="p-6 lg:p-10">

                        {{-- Header --}}
                        <div
                            class="relative z-[60] flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">Daftar Seleksi</h3>
                                <p class="text-sm text-slate-500 mt-1">Kelola proses seleksi pendaftar beasiswa.</p>
                            </div>
                            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full md:w-auto">
                                <div x-data="{ openImport: false }" class="relative w-full sm:w-auto">
                                    <button @click="openImport = true"
                                        class="w-full sm:w-auto group inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-600 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-violet-500/30 hover:from-violet-700 hover:to-blue-700 hover:shadow-violet-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                        </svg>
                                        Import Data Seleksi
                                    </button>

                                    <!-- Modal -->
                                    <div x-show="openImport" style="display:none;"
                                        class="fixed inset-0 z-[99999] overflow-y-auto" aria-labelledby="modal-title"
                                        role="dialog" aria-modal="true">
                                        <div
                                            class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            <div x-show="openImport" x-transition:enter="ease-out duration-300"
                                                x-transition:enter-start="opacity-0"
                                                x-transition:enter-end="opacity-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100"
                                                x-transition:leave-end="opacity-0"
                                                class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
                                                @click="openImport = false" aria-hidden="true"></div>

                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                                aria-hidden="true">&#8203;</span>

                                            <div x-show="openImport" x-transition:enter="ease-out duration-300"
                                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                x-transition:leave="ease-in duration-200"
                                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl shadow-slate-900/20 transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
                                                <form action="{{ route('selections.import.preview') }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                                                <h3 class="text-lg leading-6 font-bold text-slate-900"
                                                                    id="modal-title">
                                                                    Import Data Seleksi
                                                                </h3>
                                                                <div class="mt-2">
                                                                    <p class="text-sm text-slate-500 mb-4">Upload file
                                                                        Excel hasil export. Sistem akan membaca status
                                                                        dan menerapkan pembaruan (misal: "Diterima").
                                                                    </p>
                                                                    <input type="file" name="file"
                                                                        accept=".xlsx,.xls" required
                                                                        class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100 transition-colors border border-slate-200 rounded-xl cursor-pointer">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div
                                                        class="bg-slate-50 px-4 py-4 sm:px-6 sm:flex sm:flex-row-reverse border-t border-slate-100">
                                                        <button type="submit"
                                                            class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-5 py-2.5 bg-violet-600 text-base font-medium text-white hover:bg-violet-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 sm:ml-3 sm:w-auto sm:text-sm transition-colors">
                                                            Preview Data
                                                        </button>
                                                        <button type="button" @click="openImport = false"
                                                            class="mt-3 w-full inline-flex justify-center rounded-xl border border-slate-300 shadow-sm px-5 py-2.5 bg-white text-base font-medium text-slate-700 hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-500 sm:mt-0 sm:w-auto sm:text-sm transition-colors">
                                                            Batal
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative w-full sm:w-auto" x-data="{ openExport: false }">
                                    <button @click="openExport = !openExport" @click.outside="openExport = false"
                                        class="w-full sm:w-auto group inline-flex justify-center items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-700 text-sm font-semibold rounded-2xl shadow-sm hover:bg-slate-50 hover:border-slate-300 transition-all duration-200">
                                        <svg class="w-4 h-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Export Laporan
                                        <svg class="w-4 h-4 text-slate-400 transition-transform duration-200"
                                            :class="openExport ? 'rotate-180' : ''" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>

                                    <div x-show="openExport" x-transition:enter="transition ease-out duration-150"
                                        x-transition:enter-start="opacity-0 translate-y-1"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        x-transition:leave="transition ease-in duration-100"
                                        x-transition:leave-start="opacity-100 translate-y-0"
                                        x-transition:leave-end="opacity-0 translate-y-1"
                                        class="absolute right-0 mt-2 w-48 bg-white border border-slate-100 rounded-2xl shadow-xl shadow-slate-200/60 z-[9999] overflow-hidden"
                                        style="display:none;">
                                        <a href="{{ route('selections.export', array_merge(request()->query(), ['format' => 'excel'])) }}"
                                            class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 transition-colors group">
                                            <div
                                                class="w-8 h-8 rounded-xl bg-emerald-100 group-hover:bg-emerald-200 flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4 text-emerald-600"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div>Excel (.xlsx)</div>
                                                <div class="text-xs text-slate-400 font-normal">Spreadsheet</div>
                                            </div>
                                        </a>
                                        <div class="border-t border-slate-50"></div>
                                        <a href="{{ route('selections.export', array_merge(request()->query(), ['format' => 'pdf'])) }}"
                                            class="flex items-center gap-3 px-4 py-3 text-sm font-semibold text-slate-700 hover:bg-rose-50 hover:text-rose-700 transition-colors group">
                                            <div
                                                class="w-8 h-8 rounded-xl bg-rose-100 group-hover:bg-rose-200 flex items-center justify-center transition-colors">
                                                <svg class="w-4 h-4 text-rose-600" xmlns="http://www.w3.org/2000/svg"
                                                    fill="none" viewBox="0 0 24 24" stroke-width="2"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                                </svg>
                                            </div>
                                            <div>
                                                <div>PDF (.pdf)</div>
                                                <div class="text-xs text-slate-400 font-normal">Dokumen cetak</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                                @hasrole('admin|staf')
                                    <a href="{{ route('selections.fuzzy-preview') }}"
                                        class="w-full sm:w-auto group inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-violet-600 to-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-violet-500/30 hover:from-violet-700 hover:to-blue-700 hover:shadow-violet-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                        {{-- AI Sparkle Star Icon --}}
                                        <span class="text-xl leading-none">✦</span>
                                        Seleksi dengan AI
                                    </a>
                                @endhasrole
                            </div>
                        </div>

                        {{-- Filter --}}
                        <div class="mb-8 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 relative z-10">
                            <form id="filter-form" method="GET" action="{{ route('selections.index') }}"
                                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-4 items-end relative z-10">

                                <!-- Cari Mahasiswa -->
                                <div class="flex flex-col xl:col-span-4">
                                    <label for="filter_search"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Pencarian</label>
                                    <div class="relative group">
                                        <input type="text" id="filter_search" name="search"
                                            value="{{ $filters['search'] ?? '' }}"
                                            placeholder="Cari nama atau NPM mahasiswa..."
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] pr-10 text-[13px] font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm min-h-[38px] text-slate-600"
                                            @input.debounce.500ms="$el.closest('form').submit()">
                                        <div
                                            class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="2.5"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter Beasiswa -->
                                <div class="flex flex-col xl:col-span-2">
                                    <label for="filter_scholarship"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Beasiswa</label>
                                    <x-searchable-dropdown name="scholarship_id" id="filter_scholarship"
                                        placeholder="Semua Beasiswa" :options="$scholarships
                                            ->map(fn($s) => ['id' => $s->id, 'name' => $s->scholarship_name])
                                            ->prepend(['id' => '', 'name' => 'Semua Beasiswa'])" :value="$filters['scholarship_id'] ?? ''"
                                        :showFooter="false" compact />
                                </div>

                                <!-- Filter Tahap -->
                                <div class="flex flex-col xl:col-span-2">
                                    <label for="filter_stage"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Tahap</label>
                                    <x-searchable-dropdown name="stage" id="filter_stage" placeholder="Semua Tahap"
                                        :options="collect($stages)
                                            ->map(fn($s) => ['id' => $s, 'name' => $s])
                                            ->prepend(['id' => '', 'name' => 'Semua Tahap'])" :value="$filters['stage'] ?? ''" :showFooter="false" compact />
                                </div>

                                <!-- Filter Status -->
                                <div class="flex flex-col xl:col-span-2">
                                    <label for="filter_status"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Status</label>
                                    <x-searchable-dropdown name="status" id="filter_status"
                                        placeholder="Semua Status" :options="collect([
                                            ['id' => '', 'name' => 'Semua Status'],
                                            ['id' => 'belum diverifikasi', 'name' => 'Belum Diverifikasi'],
                                            ['id' => 'verifikasi', 'name' => 'Diverifikasi'],
                                            ['id' => 'wawancara', 'name' => 'Wawancara'],
                                            ['id' => 'siap di proses', 'name' => 'Siap di Proses'],
                                            ['id' => 'layak', 'name' => 'Layak'],
                                            ['id' => 'diterima', 'name' => 'Diterima'],
                                            ['id' => 'tidak diterima', 'name' => 'Tidak Diterima'],
                                        ])" :value="$filters['status'] ?? ''"
                                        :showFooter="false" compact />
                                </div>

                                <!-- Terapkan -->
                                <div class="flex flex-col xl:col-span-2">
                                    <button type="submit"
                                        class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-black text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700 hover:shadow-blue-600/40 transform hover:-translate-y-0.5 min-h-[38px]">
                                        Terapkan
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Table --}}
                        <div class="rounded-2xl border border-slate-200 bg-white">
                            <div class="overflow-x-auto w-full">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                No</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Mahasiswa</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Beasiswa</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Tahap</th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Status</th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Tanggal</th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse($data as $key => $item)
                                            <tr class="group hover:bg-blue-50/40 transition-colors duration-200">
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                    {{ $data->firstItem() + $key }}
                                                </td>
                                                <td class="px-6 py-4 text-left">
                                                    <div class="font-bold text-blue-600">
                                                        {{ $item->application->student->name }}</div>
                                                    <div class="text-xs text-slate-500">
                                                        {{ $item->application->student->student_number }}</div>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-semibold text-slate-700">
                                                    {{ $item->application->scholarship->scholarship_name }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm text-slate-600">
                                                    {{ $item->stage }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if ($item->status == 'belum diverifikasi')
                                                        <span
                                                            class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                                            <div
                                                                class="flex items-center justify-center w-5 h-5 rounded-full bg-slate-400 text-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3 w-3" viewBox="0 0 20 20"
                                                                    fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            Belum Diverifikasi
                                                        </span>
                                                    @elseif($item->status == 'layak')
                                                        <span
                                                            class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-violet-50 text-violet-700 border border-violet-100/50">
                                                            <div
                                                                class="flex items-center justify-center w-5 h-5 rounded-full bg-gradient-to-br from-violet-500 to-blue-500 text-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3 w-3" viewBox="0 0 20 20"
                                                                    fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            Layak
                                                        </span>
                                                    @elseif($item->status == 'diterima')
                                                        <span
                                                            class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100/50">
                                                            <div
                                                                class="flex items-center justify-center w-5 h-5 rounded-full bg-emerald-500 text-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                                                    fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            Diterima
                                                        </span>
                                                    @elseif($item->status == 'wawancara')
                                                        <span
                                                            class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-blue-50 text-blue-600 border border-blue-100/50">
                                                            <div
                                                                class="flex items-center justify-center w-5 h-5 rounded-full bg-blue-500 text-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3 w-3" viewBox="0 0 20 20"
                                                                    fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            Wawancara
                                                        </span>
                                                    @elseif($item->status == 'siap di proses')
                                                        <span
                                                            class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100/50">
                                                            <div
                                                                class="flex items-center justify-center w-5 h-5 rounded-full bg-indigo-500 text-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3 w-3" viewBox="0 0 20 20"
                                                                    fill="currentColor">
                                                                    <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                                                    <path fill-rule="evenodd"
                                                                        d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            Siap di Proses
                                                        </span>
                                                    @elseif($item->status == 'verifikasi')
                                                        <span
                                                            class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-amber-50 text-amber-600 border border-amber-100/50">
                                                            <div
                                                                class="flex items-center justify-center w-5 h-5 rounded-full bg-amber-500 text-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3 w-3" viewBox="0 0 20 20"
                                                                    fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            Diverifikasi
                                                        </span>
                                                    @elseif($item->status == 'tidak diterima')
                                                        <span
                                                            class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-100/50">
                                                            <div
                                                                class="flex items-center justify-center w-5 h-5 rounded-full bg-rose-500 text-white">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="h-3.5 w-3.5" viewBox="0 0 20 20"
                                                                    fill="currentColor">
                                                                    <path fill-rule="evenodd"
                                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                                        clip-rule="evenodd" />
                                                                </svg>
                                                            </div>
                                                            Tidak Diterima
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-500 border border-slate-200">
                                                            {{ $item->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-600">
                                                    {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="relative flex justify-center" x-data="{
                                                        open: false,
                                                        top: 0,
                                                        left: 0,
                                                        toggle(el) {
                                                            this.open = !this.open;
                                                            if (this.open) {
                                                                const r = el.getBoundingClientRect();
                                                                this.top = r.bottom + window.scrollY + 4;
                                                                this.left = r.right + window.scrollX - 144;
                                                            }
                                                        }
                                                    }">

                                                        <button @click="toggle($el)"
                                                            class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all">
                                                            <svg width="3" height="15" viewBox="0 0 3 15"
                                                                fill="none">
                                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                                    d="M0 1.5C4.47035e-08 0.671573 0.671573 0 1.5 0C2.32843 4.47035e-08 3 0.671573 3 1.5C3 2.32843 2.32843 3 1.5 3C0.671573 3 0 2.32843 0 1.5ZM0 7.5C4.47035e-08 6.67157 0.671573 6 1.5 6C2.32843 6 3 6.67157 3 7.5C3 8.32843 2.32843 9 1.5 9C0.671573 9 0 8.32843 0 7.5ZM0 13.5C4.47035e-08 12.6716 0.671573 12 1.5 12C2.32843 12 3 12.6716 3 13.5C3 14.3284 2.32843 15 1.5 15C0.671573 15 0 14.3284 0 13.5Z"
                                                                    fill="#555555" />
                                                            </svg>
                                                        </button>

                                                        <div x-show="open" @click.outside="open=false"
                                                            x-transition:enter="transition ease-out duration-150"
                                                            x-transition:enter-start="opacity-0 scale-95"
                                                            x-transition:enter-end="opacity-100 scale-100"
                                                            x-transition:leave="transition ease-in duration-100"
                                                            x-transition:leave-start="opacity-100 scale-100"
                                                            x-transition:leave-end="opacity-0 scale-95"
                                                            :style="'position:fixed;z-index:9999;width:144px;top:' + top +
                                                                'px;left:' + left + 'px'"
                                                            class="rounded-xl bg-white shadow-xl border border-slate-100 overflow-hidden origin-top-right"
                                                            style="display:none;">
                                                            <a href="{{ route('selections.show', $item->id) }}"
                                                                class="w-full block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">Detail</a>
                                                            @hasrole('admin|staf')
                                                                <a href="{{ route('selections.edit', $item->id) }}"
                                                                    class="w-full block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">Ubah</a>
                                                                <form
                                                                    action="{{ route('selections.destroy', $item->id) }}"
                                                                    method="POST"
                                                                    data-confirm-message="Yakin ingin menghapus data seleksi ini? Tindakan ini tidak dapat dibatalkan.">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="w-full text-left px-4 py-2.5 text-sm font-semibold text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition-colors">Hapus</button>
                                                                </form>
                                                            @endhasrole
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-10 w-10 text-slate-300" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada data
                                                            seleksi</h3>
                                                        <p class="text-sm text-slate-500 mt-1">Belum ada data seleksi
                                                            yang dibuat.</p>
                                                        {{-- <a href="{{ route('selections.create') }}" class="mt-4 inline-block text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">+ Tambah Seleksi</a> --}}
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6">{{ $data->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
