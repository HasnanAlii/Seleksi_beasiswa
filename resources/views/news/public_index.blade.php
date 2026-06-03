<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Berita & Pengumuman - Sistem Seleksi Beasiswa</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/icon/logoft.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/icon/logoft.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/icon/logoft.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('images/icon/logoft.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700,800,900" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-[#f8fafc] text-slate-800 font-sans antialiased min-h-screen relative overflow-x-hidden selection:bg-blue-500 selection:text-white">

        {{-- Background Decorations --}}
        <div class="absolute top-0 left-0 w-full h-[500px] bg-gradient-to-b from-blue-50/80 to-transparent -z-10 pointer-events-none"></div>

        {{-- Navigation --}}
        <x-public-header />

        <div class="max-w-7xl mx-auto px-6 lg:px-8 mt-8">

            <main class="pb-16">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                    <div>
                        <h1 class="text-4xl font-black text-slate-800 tracking-tight">Berita & Pengumuman</h1>
                        <p class="text-slate-500 mt-2">Dapatkan informasi terbaru tentang beasiswa dan seleksi</p>
                    </div>

                    <form action="{{ route('news.public_index') }}" method="GET" class="relative max-w-sm w-full">
                        <input type="text" name="search" value="{{ $search }}" placeholder="Cari berita..." class="w-full pl-10 pr-4 py-3 rounded-2xl border-slate-200 focus:border-blue-500 focus:ring focus:ring-blue-500/20 shadow-sm transition-shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 absolute left-3.5 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </form>
                </div>

                @if($news->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($news as $item)
                            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden flex flex-col hover:-translate-y-1 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300">
                                @if($item->media && $item->media->count() > 0)
                                    <div class="aspect-video w-full bg-slate-100 overflow-hidden relative">
                                        <img src="{{ asset('storage/' . $item->media->first()->file) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="aspect-video w-full bg-blue-50 flex items-center justify-center relative overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-br from-blue-100/50 to-indigo-100/50"></div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-blue-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                        </svg>
                                    </div>
                                @endif
                                
                                <div class="p-6 flex-1 flex flex-col">
                                    <div class="flex items-center gap-2 mb-4 text-xs font-bold text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        {{ $item->created_at->format('d M Y') }}
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 leading-tight mb-3 line-clamp-2">
                                        {{ $item->title }}
                                    </h3>
                                    <div class="text-slate-600 text-sm line-clamp-3 mb-6 flex-1">
                                        {!! Str::limit(strip_tags($item->content), 120) !!}
                                    </div>
                                    
                                    <a href="{{ route('news.read', $item->id) }}" class="inline-flex items-center gap-2 text-blue-600 font-bold text-sm hover:text-blue-700 transition-colors group">
                                        Baca Selengkapnya
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-10">
                        {{ $news->links() }}
                    </div>
                @else
                    <div class="text-center py-20 bg-white rounded-3xl border border-slate-100 shadow-sm">
                        <div class="inline-flex justify-center items-center w-20 h-20 rounded-full bg-slate-50 text-slate-400 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-2">Berita tidak ditemukan</h3>
                        <p class="text-slate-500 font-medium">Belum ada berita atau pengumuman yang sesuai.</p>
                        @if($search)
                            <a href="{{ route('news.public_index') }}" class="inline-block mt-4 text-blue-600 font-bold hover:underline">Tampilkan semua berita</a>
                        @endif
                    </div>
                @endif
            </main>
        </div>

        {{-- Footer --}}
        <x-public-footer />
    </body>
</html>
