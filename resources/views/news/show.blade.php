<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Detail Berita') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('news.index') }}" class="hover:text-blue-600 cursor-pointer transition">Berita</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail Data</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12  bg-[#f0f6ff] min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <div class="lg:col-span-1">
                    <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100 group hover:shadow-2xl transition-all duration-500">
                        {{-- Photo Section --}}
                        <div class="relative h-64 w-full bg-slate-100 overflow-hidden">
                            @php
                                $cover = $news->media->first();
                            @endphp
                            @if($cover)
                                <img src="{{ asset('storage/' . $cover->file) }}" class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Cover Berita">
                            @else
                                <div class="flex flex-col items-center justify-center h-full text-slate-300">
                                    <svg class="w-16 h-16 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span class="text-[10px] uppercase font-black tracking-widest">Tidak ada media</span>
                                </div>
                            @endif
                        </div>

                        <div class="p-6 lg:p-8">
                            <div class="space-y-5">
                                <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Dibuat Pada</span>
                                    <span class="text-slate-800 font-extrabold text-sm">{{ $news->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between items-center border-b border-slate-50 pb-4">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Diupdate Pada</span>
                                    <span class="text-slate-800 font-extrabold text-sm">{{ $news->updated_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-widest">Jumlah Media</span>
                                    <span class="text-blue-600 font-black tracking-tight">{{ $news->media->count() }} Foto</span>
                                </div>
                            </div>
                            
                            <div class="mt-10 pt-6 border-t border-slate-100">
                                <a href="{{ route('news.edit', $news) }}" 
                                   class="block w-full text-center py-4 bg-slate-900 text-white text-[11px] font-black uppercase tracking-[0.2em] rounded-2xl hover:bg-blue-600 shadow-xl shadow-slate-200 transition-all duration-300 transform hover:-translate-y-1">
                                    Edit Berita
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Additional Photos Gallery --}}
                    @if($news->media->count() > 1)
                        <div class="mt-8 bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100 p-6 lg:p-8">
                            <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.3em] mb-6">Galeri Media Pendukung</h3>
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($news->media->skip(1) as $p)
                                    <div class="relative aspect-square rounded-[1.5rem] overflow-hidden border border-slate-100 group/gallery">
                                        <img src="{{ asset('storage/' . $p->file) }}" class="w-full h-full object-cover transition-transform duration-500 group-hover/gallery:scale-125">
                                        <div class="absolute inset-0 bg-black/20 opacity-0 group-hover/gallery:opacity-100 transition-opacity flex items-center justify-center">
                                            <svg class="text-white w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" /></svg>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-2">
                    <div class="space-y-8">
                        {{-- NEWS CONTENT --}}
                        <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100">
                            <div class="p-6 lg:p-8">
                                
                                <div class="mb-6">
                                    <h3 class="text-2xl font-black text-slate-800 leading-tight mb-2">{{ $news->title }}</h3>
                                    <div class="w-20 h-1 bg-blue-500 rounded-full"></div>
                                </div>

                                <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed whitespace-pre-wrap">
                                    {{ $news->content }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
