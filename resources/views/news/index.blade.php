<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                Berita
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Berita</span>
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
                                <h3 class="text-xl font-bold text-slate-800">Data Berita</h3>
                                <p class="text-sm text-slate-500 mt-1">Kelola data berita beasiswa.</p>
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('news.create') }}"
                                    class="group inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white text-sm font-semibold rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all duration-300 transform hover:-translate-y-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5 transition-transform group-hover:rotate-90" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    Tambah Berita
                                </a>
                            </div>
                        </div>

                        <!-- Filter Bar -->
                        <div class="mb-8 rounded-2xl border border-slate-200 bg-slate-50/70 p-4 relative z-10">
                            <form id="filter-form" method="GET" action="{{ route('news.index') }}"
                                class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-4 items-end relative z-10">
                                <!-- Cari Judul -->
                                <div class="flex flex-col xl:col-span-6">
                                    <label for="filter_search"
                                        class="mb-1.5 block text-[10px] font-black uppercase tracking-[0.1em] text-slate-400">Pencarian</label>
                                    <div class="relative group">
                                        <input type="text" id="filter_search" name="search"
                                            value="{{ $filters['search'] ?? '' }}" placeholder="Cari judul berita..."
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
                                            {{-- <th class="px-6 py-4 text-center text-xs font-bold text-slate-500 uppercase tracking-wider">Foto</th> --}}
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Judul Berita</th>
                                            <th
                                                class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">
                                                Isi Berita</th>
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
                                                {{-- <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="inline-flex h-10 w-10 overflow-hidden rounded-xl bg-slate-100 border border-slate-200 shadow-sm">
                                                        @php $cover = $item->media->first(); @endphp
                                                        @if ($cover)
                                                            <img src="{{ asset('storage/' . $cover->file) }}" class="h-full w-full object-cover">
                                                        @else
                                                            <div class="flex h-full w-full items-center justify-center text-slate-300">
                                                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                                </svg>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </td> --}}
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-left text-sm font-bold text-blue-600">
                                                    {{ $item->title }}</td>
                                                <td
                                                    class="px-6 py-4 text-left text-sm text-slate-500 max-w-md truncate">
                                                    {{ $item->content }}</td>
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-center text-sm text-slate-500">
                                                    {{ $item->created_at->translatedFormat('d M Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                                    <div class="relative flex justify-center" x-data="{ open: false, top: 0, left: 0, toggle(el) { this.open = !this.open; if (this.open) { const r = el.getBoundingClientRect();
                                                                this.top = r.bottom + window.scrollY + 4;
                                                                this.left = r.right + window.scrollX - 144; } } }">
                                                        <button @click="toggle($el)"
                                                            class="p-2 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-lg transition-all">
                                                            <svg width="3" height="15" viewBox="0 0 3 15"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                                            <a href="{{ route('news.show', $item->id) }}"
                                                                class="w-full block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                                Detail
                                                            </a>
                                                            <a href="{{ route('news.edit', $item->id) }}"
                                                                class="w-full block px-4 py-2.5 text-sm text-left font-semibold text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                                                                Ubah
                                                            </a>
                                                            <form action="{{ route('news.destroy', $item->id) }}"
                                                                method="POST">
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
                                                <td colspan="5" class="px-6 py-16 text-center">
                                                    <div class="flex flex-col items-center justify-center">
                                                        <div class="bg-slate-50 p-4 rounded-full mb-4">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="h-10 w-10 text-slate-300" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                            </svg>
                                                        </div>
                                                        <h3 class="text-lg font-semibold text-slate-700">Tidak ada
                                                            berita ditemukan</h3>
                                                        <p class="text-sm text-slate-500 max-w-xs mx-auto mt-1">Silakan
                                                            tambahkan data berita baru.</p>
                                                        <a href="{{ route('news.create') }}"
                                                            class="mt-4 inline-block text-sm text-blue-600 hover:text-blue-800 font-medium hover:underline">
                                                            + Tambah Berita
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

        @include('news.script')
    </div>
</x-app-layout>
