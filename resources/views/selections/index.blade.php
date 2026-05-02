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

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="space-y-8">
                <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100">
                    <div class="p-6 lg:p-10">

                        {{-- Header --}}
                        <div class="relative z-[250] flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">Daftar Seleksi</h3>
                                <p class="text-sm text-slate-500 mt-1">Kelola proses seleksi pendaftar beasiswa.</p>
                            </div>
                            <a href="{{ route('selections.create') }}"
                                class="group inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd" />
                                </svg>
                                Tambah Seleksi
                            </a>
                        </div>

                        {{-- Filter --}}
                        <div class="mb-8 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 relative z-[100]">
                            <form method="GET" action="{{ route('selections.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-6 items-end">
                                <div class="flex flex-col xl:col-span-2">
                                    <label for="filter_search" class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Cari Mahasiswa</label>
                                    <div class="relative" x-data="{ value: '{{ $filters['search'] ?? '' }}' }">
                                        <input type="text" id="filter_search" name="search" x-model="value"
                                            placeholder="Nama atau NIM..."
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] pr-10 text-[13px] font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm min-h-[38px]"
                                            :class="value ? 'text-slate-600' : 'text-slate-400'">
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex flex-col xl:col-span-2">
                                    <label for="filter_status" class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Filter Status</label>
                                    <select id="filter_status" name="status" class="w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] text-[13px] font-bold text-slate-600 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm min-h-[38px]">
                                        <option value="">Semua Status</option>
                                        <option value="verifikasi" {{ ($filters['status'] ?? '') == 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                                        <option value="wawancara" {{ ($filters['status'] ?? '') == 'wawancara' ? 'selected' : '' }}>Wawancara</option>
                                        <option value="diterima" {{ ($filters['status'] ?? '') == 'diterima' ? 'selected' : '' }}>Diterima</option>
                                        <option value="tidak diterima" {{ ($filters['status'] ?? '') == 'tidak diterima' ? 'selected' : '' }}>Tidak Diterima</option>
                                    </select>
                                </div>
                                <div class="xl:col-span-1"></div>
                                <div class="flex flex-col">
                                    <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-2 text-[13px] font-black text-white shadow-lg shadow-blue-500/30 transition hover:bg-blue-700 hover:shadow-blue-600/40 transform hover:-translate-y-0.5 min-h-[38px]">
                                        Terapkan
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- Table --}}
                        <div class="rounded-2xl border border-slate-200 bg-white">
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-slate-100">
                                    <thead class="bg-slate-50/80">
                                        <tr>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Mahasiswa</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Beasiswa</th>
                                            <th class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Tahap</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                                            <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 bg-white">
                                        @forelse($data as $key => $item)
                                            <tr class="group hover:bg-blue-50/40 transition-colors duration-200">
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium text-slate-400">
                                                    {{ $data->firstItem() + $key }}
                                                </td>
                                                <td class="px-6 py-4 text-left">
                                                    <div class="font-bold text-blue-600">{{ $item->application->student->name }}</div>
                                                    <div class="text-xs text-slate-500">{{ $item->application->student->student_number }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm font-semibold text-slate-700">
                                                    {{ $item->application->scholarship->scholarship_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-left text-sm text-slate-600">
                                                    {{ $item->stage }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @if($item->status == 'diterima')
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                                            <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                                                            Diterima
                                                        </span>
                                                    @elseif($item->status == 'wawancara')
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                                            <div class="w-2 h-2 rounded-full bg-blue-500"></div>
                                                            Wawancara
                                                        </span>
                                                    @elseif($item->status == 'verifikasi')
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-700">
                                                            <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                                                            Verifikasi
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-700">
                                                            <div class="w-2 h-2 rounded-full bg-rose-500"></div>
                                                            Tidak Diterima
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-600">
                                                    {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="relative flex justify-center" x-data="{ open: false }">
                                                        <button @click="open = !open" @click.outside="open = false"
                                                            class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all">
                                                            <svg width="3" height="15" viewBox="0 0 3 15" fill="none"><path fill-rule="evenodd" clip-rule="evenodd" d="M0 1.5C4.47035e-08 0.671573 0.671573 0 1.5 0C2.32843 4.47035e-08 3 0.671573 3 1.5C3 2.32843 2.32843 3 1.5 3C0.671573 3 0 2.32843 0 1.5ZM0 7.5C4.47035e-08 6.67157 0.671573 6 1.5 6C2.32843 6 3 6.67157 3 7.5C3 8.32843 2.32843 9 1.5 9C0.671573 9 0 8.32843 0 7.5ZM0 13.5C4.47035e-08 12.6716 0.671573 12 1.5 12C2.32843 12 3 12.6716 3 13.5C3 14.3284 2.32843 15 1.5 15C0.671573 15 0 14.3284 0 13.5Z" fill="#555555"/></svg>
                                                        </button>
                                                        <div x-show="open"
                                                            x-transition:enter="transition ease-out duration-150"
                                                            x-transition:enter-start="opacity-0 scale-95"
                                                            x-transition:enter-end="opacity-100 scale-100"
                                                            x-transition:leave="transition ease-in duration-100"
                                                            x-transition:leave-start="opacity-100 scale-100"
                                                            x-transition:leave-end="opacity-0 scale-95"
                                                            class="absolute right-0 top-9 z-30 w-36 rounded-xl bg-white shadow-xl border border-slate-100 overflow-hidden origin-top-right"
                                                            style="display:none;">
                                                            <a href="{{ route('selections.show', $item->id) }}"
                                                                class="w-full block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">Detail</a>
                                                            <a href="{{ route('selections.edit', $item->id) }}"
                                                                class="w-full block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">Ubah</a>
                                                            <form action="{{ route('selections.destroy', $item->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" onclick="return confirm('Yakin ingin menghapus data seleksi ini?')"
                                                                    class="w-full text-left px-4 py-2.5 text-sm font-semibold text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition-colors">Hapus</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada data seleksi</h3>
                                                        <p class="text-sm text-slate-500 mt-1">Belum ada data seleksi yang dibuat.</p>
                                                        <a href="{{ route('selections.create') }}" class="mt-4 inline-block text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">+ Tambah Seleksi</a>
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
