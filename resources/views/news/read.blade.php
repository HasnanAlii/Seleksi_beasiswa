<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ $news->title }} - Sistem Seleksi Beasiswa</title>

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

        <div class="max-w-5xl mx-auto px-6 lg:px-8 mt-8">

            <main class="pb-16 grid lg:grid-cols-3 gap-12">
                {{-- Main Article --}}
                <article class="lg:col-span-2">
                    <a href="{{ route('news.public_index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-700 mb-6 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Kembali ke Berita
                    </a>

                    <div class="bg-white rounded-3xl p-6 md:p-10 shadow-xl shadow-slate-200/40 border border-slate-100">
                        <div class="flex items-center gap-3 mb-6 text-sm font-bold text-slate-400">
                            <span class="flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                {{ $news->created_at->format('d M Y') }}
                            </span>
                        </div>

                        <h1 class="text-3xl md:text-4xl font-black text-slate-800 leading-tight mb-8">
                            {{ $news->title }}
                        </h1>

                        @if($news->media && $news->media->count() > 0)
                            <div class="aspect-video w-full rounded-2xl overflow-hidden mb-10 shadow-md">
                                <img src="{{ asset('storage/' . $news->media->first()->file) }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
                            </div>
                        @endif

                        <div class="prose prose-slate prose-blue max-w-none text-slate-600 leading-relaxed whitespace-pre-wrap prose-headings:font-bold prose-headings:text-slate-800 prose-a:text-blue-600">
                            {!! $news->content !!}
                        </div>

                        {{-- Additional Gallery --}}
                        @if($news->media && $news->media->count() > 1)
                            <div class="mt-12 pt-8 border-t border-slate-100">
                                <h3 class="text-lg font-bold text-slate-800 mb-4">Galeri Foto</h3>
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                                    @foreach($news->media->skip(1) as $media)
                                        <div class="aspect-square rounded-xl overflow-hidden bg-slate-100 border border-slate-200 shadow-sm hover:shadow-md transition-shadow">
                                            <img src="{{ asset('storage/' . $media->file) }}" class="w-full h-full object-cover cursor-pointer hover:scale-110 transition-transform duration-500" alt="Galeri">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </article>

                {{-- Sidebar --}}
                <aside class="lg:col-span-1 space-y-8">
                    <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/40 border border-slate-100 sticky top-6">
                        <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                            Berita Terbaru
                        </h3>
                        
                        @if($recentNews->count() > 0)
                            <div class="space-y-6">
                                @foreach($recentNews as $recent)
                                    <a href="{{ route('news.read', $recent->id) }}" class="group block">
                                        <div class="flex gap-4">
                                            @if($recent->media && $recent->media->count() > 0)
                                                <div class="w-20 h-20 shrink-0 rounded-xl overflow-hidden bg-slate-100 border border-slate-100">
                                                    <img src="{{ asset('storage/' . $recent->media->first()->file) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="">
                                                </div>
                                            @else
                                                <div class="w-20 h-20 shrink-0 rounded-xl overflow-hidden bg-blue-50 border border-slate-100 flex items-center justify-center text-blue-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div>
                                                <h4 class="font-bold text-slate-800 text-sm leading-tight group-hover:text-blue-600 transition-colors line-clamp-2 mb-1">
                                                    {{ $recent->title }}
                                                </h4>
                                                <p class="text-xs font-semibold text-slate-400">
                                                    {{ $recent->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <p class="text-sm text-slate-500">Tidak ada berita terbaru.</p>
                        @endif
                    </div>
                </aside>
            </main>
        </div>

        {{-- Footer --}}
        <x-public-footer />
    </body>
</html>
