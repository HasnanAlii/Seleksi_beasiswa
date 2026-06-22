<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                Penilaian Wawancara
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Penilaian Wawancara</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-[#f0f6ff] min-h-screen px-3 md:px-10">
        <div class="mx-auto sm:px-4 lg:px-8">
            <div class="space-y-8">

                <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100">
                    <div class="p-6 lg:p-10">

                        {{-- Header Card --}}
                        <div
                            class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-6">
                            <div>
                                <h3 class="text-xl font-bold text-slate-800">Daftar Penilaian Wawancara</h3>
                                <p class="text-sm text-slate-500 mt-1">Kelola hasil penilaian wawancara seleksi
                                    beasiswa.</p>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('interview-assessments.create') }}"
                                    class="group inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Tambah Penilaian
                                </a>
                            </div>
                        </div>

                        <!-- Filter Bar -->
                        <div class="mb-8 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 relative z-10">
                            <form id="filter-form" method="GET" action="{{ route('interview-assessments.index') }}"
                                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-4 items-end relative z-10">

                                <div class="flex flex-col xl:col-span-6">
                                    <label for="filter_search"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Pencarian</label>
                                    <div class="relative group">
                                        <input type="text" id="filter_search" name="search"
                                            value="{{ $filters['search'] ?? '' }}"
                                            placeholder="Cari nama penilai atau mahasiswa..."
                                            class="w-full rounded-xl border border-slate-200 bg-white px-4 py-[7px] pr-10 text-[13px] font-bold focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm min-h-[38px] text-slate-600"
                                            @input.debounce.500ms="$el.closest('form').submit()">
                                        <div
                                            class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="hidden xl:block xl:col-span-4"></div>

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
                                                Penilai</th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Jadwal Wawancara</th>
                                            <th
                                                class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Nilai</th>
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
                                                        {{ $item->interview->application->student->name }}</div>
                                                    <div class="text-xs text-slate-500">
                                                        {{ $item->interview->application->scholarship->scholarship_name }}
                                                    </div>
                                                </td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-semibold text-slate-700">
                                                    {{ $item->interviewer }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="text-sm font-bold text-slate-800">
                                                        {{ $item->interview->schedule->translatedFormat('d M Y') }}
                                                    </div>
                                                    <div class="text-xs text-slate-500">
                                                        {{ $item->interview->schedule->format('H:i') }} WIB</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    @php $score = (float) $item->score; @endphp
                                                    <span
                                                        class="inline-flex items-center gap-2 px-2.5 py-1 rounded-lg text-[14px] font-black tracking-wider
                                                        {{ $score >= 80 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100/50' : ($score >= 60 ? 'bg-amber-50 text-amber-600 border border-amber-100/50' : 'bg-rose-50 text-rose-600 border border-rose-100/50') }}">
                                                        {{-- <div class="flex items-center justify-center w-5 h-5 rounded-full text-white {{ $score >= 80 ? 'bg-emerald-500' : ($score >= 60 ? 'bg-amber-500' : 'bg-rose-500') }}">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" viewBox="0 0 20 20" fill="currentColor">
                                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                            </svg>
                                                        </div> --}}
                                                        {{ number_format($score, 2) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="relative flex justify-center" x-data="{ open: false, top: 0, left: 0, toggle(el) { this.open = !this.open; if (this.open) { const r = el.getBoundingClientRect();
                                                                this.top = r.bottom + window.scrollY + 4;
                                                                this.left = r.right + window.scrollX - 144; } } }">
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
                                                            <a href="{{ route('interview-assessments.show', $item->id) }}"
                                                                class="w-full block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                                Detail
                                                            </a>
                                                            <a href="{{ route('interview-assessments.edit', $item->id) }}"
                                                                class="w-full block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                                Ubah
                                                            </a>
                                                            <form
                                                                action="{{ route('interview-assessments.destroy', $item->id) }}"
                                                                method="POST"
                                                                data-confirm-message="Yakin ingin menghapus penilaian ini? Tindakan ini tidak dapat dibatalkan.">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="w-full text-left px-4 py-2.5 text-sm font-semibold text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition-colors">
                                                                    Hapus
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-10 w-10 text-slate-300" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada
                                                            penilaian ditemukan</h3>
                                                        <p class="text-sm text-slate-500 max-w-xs mx-auto mt-1">Belum
                                                            ada data penilaian wawancara yang dimasukkan.</p>
                                                        <a href="{{ route('interview-assessments.create') }}"
                                                            class="mt-4 inline-block text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                                            + Tambah Penilaian
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mt-6">
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
