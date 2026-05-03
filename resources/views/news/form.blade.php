<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">{{ $news->exists ? 'Ubah Berita' : 'Tambah Berita' }}</h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('news.index') }}" class="transition hover:text-blue-600">Berita</a>
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
                        <h3 class="text-xl font-bold text-slate-800">{{ $news->exists ? 'Ubah Data Berita' : 'Form Tambah Berita' }}</h3>
                        <p class="mt-1 text-sm text-slate-500">Masukkan data informasi berita dan upload media pendukung dengan lengkap.</p>
                    </div>

                    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" data-ajax-form>
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="mb-10 space-y-10 border-b border-slate-100 pb-10">
                            <!-- Additional Gallery -->
                            <div x-data="{
                                files: [],
                                previews: [],
                                dragOver: false,
                                existingPhotos: [
                                    @foreach ($news->media ?? [] as $p)
                                        { id: {{ $p->id }}, path: '{{ asset('storage/' . $p->file) }}', deleting: false },
                                    @endforeach
                                ],
                                addFiles(fileList) {
                                    Array.from(fileList).forEach(file => {
                                        if (!file.type.startsWith('image/')) return;
                                        this.files.push(file);
                                        const reader = new FileReader();
                                        reader.onload = (e) => this.previews.push(e.target.result);
                                        reader.readAsDataURL(file);
                                    });
                                    this.$refs.galleryInput.value = '';
                                },
                                removeNew(index) {
                                    this.files.splice(index, 1);
                                    this.previews.splice(index, 1);
                                },
                                async deleteExisting(photo) {
                                    if (!confirm('Hapus foto ini?')) return;
                                    photo.deleting = true;
                                    try {
                                        const token = document.querySelector('meta[name=csrf-token]').content;
                                        const res = await fetch(`/news-media/${photo.id}`, {
                                            method: 'DELETE',
                                            headers: { 'X-CSRF-TOKEN': token, 'Accept': 'application/json' }
                                        });
                                        const data = await res.json();
                                        if (data.success) {
                                            this.existingPhotos = this.existingPhotos.filter(p => p.id !== photo.id);
                                        }
                                    } catch(e) {
                                        alert('Gagal menghapus foto.');
                                    } finally {
                                        photo.deleting = false;
                                    }
                                }
                            }" class="space-y-4"
                            @dragover.prevent="dragOver = true"
                            @dragleave.prevent="dragOver = false"
                            @drop.prevent="dragOver = false; addFiles($event.dataTransfer.files)">
                                <label class="block text-center text-[10px] font-black uppercase tracking-[0.2em] text-slate-400">
                                    Galeri Foto Berita
                                </label>

                                <!-- Drop Zone -->
                                <div @click="$refs.galleryInput.click()"
                                    :class="dragOver ? 'border-blue-400 bg-blue-50/40' : 'border-slate-100 bg-white/50 hover:border-blue-400 hover:bg-blue-50/30'"
                                    class="relative group h-32 w-full border-2 border-dashed rounded-[2rem] overflow-hidden transition-all cursor-pointer flex flex-col items-center justify-center space-y-2">
                                    <input type="file" name="additional_photos[]" x-ref="galleryInput"
                                        class="hidden" accept="image/*" multiple
                                        @change="addFiles($event.target.files)">
                                    <div class="p-2 bg-white rounded-xl shadow-sm text-slate-300 group-hover:text-blue-500 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Klik atau drag &amp; drop foto</p>
                                    <p class="text-[10px] text-slate-300">JPG, PNG, WEBP • Maks 4MB per foto</p>
                                </div>

                                <!-- Count info -->
                                <p x-show="files.length > 0" class="text-[11px] text-blue-600 font-bold text-center">
                                    <span x-text="files.length"></span> foto baru akan diupload
                                </p>

                                <!-- Grid View -->
                                <div class="grid grid-cols-4 sm:grid-cols-6 lg:grid-cols-8 gap-3">
                                    <!-- Existing -->
                                    <template x-for="(photo, i) in existingPhotos" :key="photo.id">
                                        <div class="relative aspect-square rounded-2xl overflow-hidden border border-slate-100 shadow-sm group/edit"
                                            :class="photo.deleting ? 'opacity-40 pointer-events-none' : ''">
                                            <img :src="photo.path" class="h-full w-full object-cover">
                                            <!-- Delete overlay -->
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/edit:opacity-100 transition-opacity flex items-center justify-center">
                                                <button type="button" @click.stop="deleteExisting(photo)"
                                                    class="w-7 h-7 bg-rose-500 hover:bg-rose-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                            <!-- Loading spinner -->
                                            <div x-show="photo.deleting"
                                                class="absolute inset-0 bg-white/60 flex items-center justify-center">
                                                <svg class="animate-spin w-5 h-5 text-rose-500" fill="none" viewBox="0 0 24 24">
                                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- New Previews -->
                                    <template x-for="(src, index) in previews" :key="'new-' + index">
                                        <div class="relative aspect-square rounded-2xl overflow-hidden border-2 border-blue-200 shadow-inner group/new">
                                            <img :src="src" class="h-full w-full object-cover">
                                            <div class="absolute top-1 left-1 w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
                                            <!-- Remove new -->
                                            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover/new:opacity-100 transition-opacity flex items-center justify-center">
                                                <button type="button" @click.stop="removeNew(index)"
                                                    class="w-7 h-7 bg-rose-500 hover:bg-rose-600 text-white rounded-full flex items-center justify-center shadow-lg transition-all">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                @error('additional_photos.*')
                                    <p class="mt-2 text-center text-xs text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-6 space-y-6">
                            <div>
                                <label for="title" class="mb-2 block text-sm font-semibold text-slate-700">Judul Berita <span class="text-rose-500">*</span></label>
                                <input type="text" name="title" id="title"
                                    value="{{ old('title', $news->title) }}" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-600 transition-all focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="Masukkan Judul Berita">
                                @error('title')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="content" class="mb-2 block text-sm font-semibold text-slate-700">Isi Berita <span class="text-rose-500">*</span></label>
                                <textarea name="content" id="content" rows="6" required
                                    class="w-full rounded-xl border border-slate-200 bg-white px-4 py-3 text-slate-600 transition-all focus:border-blue-400 focus:ring-4 focus:ring-blue-500/10"
                                    placeholder="Masukkan Isi Berita">{{ old('content', $news->content) }}</textarea>
                                @error('content')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('news.index') }}"
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

    <!-- Sync accumulated gallery files to file input before form submit -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form[enctype="multipart/form-data"]');
            if (form) {
                form.addEventListener('submit', () => {
                    const galleryEl = document.querySelector('[x-ref="galleryInput"]');
                    if (!galleryEl) return;
                    
                    const alpineComp = Alpine.$data(galleryEl.closest('[x-data]'));
                    if (!alpineComp || !alpineComp.files || alpineComp.files.length === 0) return;

                    const dt = new DataTransfer();
                    alpineComp.files.forEach(f => dt.items.add(f));
                    galleryEl.files = dt.files;
                });
            }
        });
    </script>
</x-app-layout>