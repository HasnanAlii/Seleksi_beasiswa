<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">{{ $announcement->exists ? 'Ubah Pengumuman' : 'Tambah Pengumuman' }}</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('announcements.index') }}" class="transition hover:text-blue-600">Pengumuman</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Form</span>
            </nav>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#f0f6ff] px-10 py-12">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="rounded-3xl border border-slate-100 bg-white shadow-xl shadow-slate-200/60">
                <div class="p-6 lg:p-10">
                    <div class="mb-8">
                        <h3 class="text-xl font-bold text-slate-800">{{ $announcement->exists ? 'Ubah Data Pengumuman' : 'Form Tambah Pengumuman' }}</h3>
                        <p class="mt-1 text-sm text-slate-500">Lengkapi form di bawah ini dengan data pengumuman yang sesuai.</p>
                    </div>

                    <form action="{{ $action }}" method="POST" data-ajax-form>
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="mb-6 space-y-6">
                            
                            <div>
                                <label for="scholarship_id" class="mb-2 block text-sm font-semibold text-slate-700">Beasiswa <span class="text-rose-500">*</span></label>
                                <x-searchable-dropdown 
                                    name="scholarship_id" 
                                    id="scholarship_id" 
                                    placeholder="Pilih Beasiswa"
                                    :options="$scholarships->map(fn($s) => ['id' => $s->id, 'name' => $s->scholarship_name])"
                                    :value="old('scholarship_id', $announcement->scholarship_id)"
                                    :showFooter="false"
                                />
                                @error('scholarship_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Judul Pengumuman <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" id="title"
                                    value="{{ old('title', $announcement->title) }}" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-600 transition-all focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="Masukkan Judul Pengumuman">
                                @error('title')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="date" class="mb-2 block text-sm font-semibold text-slate-700">Tanggal Pengumuman <span class="text-rose-500">*</span></label>
                                <input type="date" name="date" id="date"
                                    value="{{ old('date', $announcement->date ? $announcement->date->format('Y-m-d') : '') }}" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-600 transition-all focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10">
                                @error('date')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="publish_status" class="mb-2 block text-sm font-semibold text-slate-700">Status Publikasi <span class="text-rose-500">*</span></label>
                                <x-searchable-dropdown 
                                    name="publish_status" 
                                    id="publish_status" 
                                    placeholder="Pilih Status"
                                    :options="[
                                        ['id' => 1, 'name' => 'Dipublikasi'],
                                        ['id' => 0, 'name' => 'Draft'],
                                    ]"
                                    :value="old('publish_status', $announcement->publish_status)"
                                    :showFooter="false"
                                    compact
                                />
                                @error('publish_status')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('announcements.index') }}"
                                class="border border-slate-200 rounded-2xl bg-transparent hover:bg-slate-50/50 text-center px-6 py-3 font-medium text-slate-500 transition-colors hover:text-slate-700">Batal</a>
                            <button type="submit"
                                class="transform rounded-2xl bg-blue-600 px-8 py-3.5 font-black text-white shadow-lg shadow-blue-500/30 transition-all duration-300 hover:-translate-y-0.5 hover:bg-blue-700 hover:shadow-blue-600/40">
                                {{ $submitLabel }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
