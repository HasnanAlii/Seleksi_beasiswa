<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Sistem Seleksi Beasiswa') }}</title>
        <!-- Favicon lengkap -->
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
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-blue-100/40 rounded-full blur-3xl -z-10 pointer-events-none"></div>
        <div class="absolute top-40 -left-40 w-[500px] h-[500px] bg-indigo-100/30 rounded-full blur-3xl -z-10 pointer-events-none"></div>

        {{-- Navigation --}}
        <x-public-header />

        {{-- Hero Section --}}
        <main class="relative w-full overflow-hidden shadow-2xl mb-16 lg:mb-24 flex items-center min-h-[500px] lg:min-h-[600px]">
            {{-- Background Image --}}
            <img src="{{ asset('images/hero-illustration.png') }}" alt="Gedung Fakultas Teknik UNSUR" class="absolute inset-0 w-full h-full object-cover z-0">
            
            {{-- Overlay Gradient for Text Readability --}}
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/95 via-slate-900/70 to-slate-900/20 z-0"></div>
            <div class="absolute inset-0 bg-blue-900/40 mix-blend-multiply z-0"></div>

            {{-- Hero Content --}}
            <div class="relative z-10 w-full max-w-7xl mx-auto px-6 lg:px-8 py-20 lg:py-24">
                <div class="max-w-3xl">
                  

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white leading-[1.1] tracking-tight mb-6 drop-shadow-lg">
                        Sistem Informasi Seleksi <br class="hidden lg:block" />
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-indigo-200">Beasiswa Mahasiswa</span>
                    </h1>

                    <p class="text-lg lg:text-xl text-slate-200 leading-relaxed mb-10 max-w-2xl drop-shadow">
                        Layanan resmi pendaftaran dan seleksi beasiswa terpadu untuk mahasiswa Fakultas Teknik Universitas Suryakancana. Wujudkan prestasi dan masa depan cemerlang.
                    </p>

                    <div class="flex flex-wrap items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex justify-center items-center gap-2 px-8 py-4 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-500/30 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                                Menuju Dashboard
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex justify-center items-center gap-2 px-8 py-4 bg-blue-600 text-white font-black rounded-2xl shadow-xl shadow-blue-500/30 hover:bg-blue-700 hover:-translate-y-1 transition-all">
                                    Mulai Mendaftar
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            @endif
                            <a href="{{ route('login') }}" class="inline-flex justify-center items-center gap-2 px-8 py-4 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl border border-white/20 shadow-sm ring-1 ring-inset ring-transparent hover:bg-white/20 hover:-translate-y-1 transition-all">
                                Masuk Akun
                            </a>
                        @endauth
                    </div>

                    <div class="mt-12 flex flex-wrap items-center gap-6 text-sm font-semibold text-blue-100/80">
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-blue-500/20 rounded-full text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            Proses Transparan
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="p-1.5 bg-blue-500/20 rounded-full text-blue-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            Akses Mudah
                        </div>
                    </div>
                </div>
            </main>

        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            
 {{-- News & Announcements Section --}}
            <section class="py-16 border-t border-slate-200/60">
                <div class="flex items-center justify-between mb-10">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight">Berita & Pengumuman</h2>
                        <p class="text-slate-500 mt-2">Informasi terbaru seputar pendaftaran beasiswa</p>
                    </div>
                </div>

                @if($latestNews->count() > 0)
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        @foreach($latestNews as $news)
                            <div class="bg-white rounded-3xl border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden flex flex-col hover:-translate-y-1 hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300">
                                @if($news->media && $news->media->count() > 0)
                                    <div class="aspect-video w-full bg-slate-100 overflow-hidden relative">
                                        <img src="{{ asset('storage/' . $news->media->first()->file) }}" alt="{{ $news->title }}" class="w-full h-full object-cover">
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
                                        {{ $news->created_at->format('d M Y') }}
                                    </div>
                                    <h3 class="text-xl font-bold text-slate-800 leading-tight mb-3 line-clamp-2">
                                        {{ $news->title }}
                                    </h3>
                                    <div class="text-slate-600 text-sm line-clamp-3 mb-6 flex-1">
                                        {!! Str::limit(strip_tags($news->content), 120) !!}
                                    </div>
                                    
                                    <a href="{{ route('news.read', $news->id) }}" class="inline-flex items-center gap-2 text-blue-600 font-bold text-sm hover:text-blue-700 transition-colors group">
                                        Baca Selengkapnya
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 bg-white rounded-3xl border border-slate-100 shadow-sm">
                        <div class="inline-flex justify-center items-center w-16 h-16 rounded-full bg-slate-50 text-slate-400 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">Belum ada berita atau pengumuman saat ini.</p>
                    </div>
                @endif
            </section>

            {{-- Alur Pendaftaran Section --}}
            <section id="panduan-pendaftaran" class="py-20">
                <div class="flex flex-col lg:flex-row gap-16 items-center">
                    <div class="w-full lg:w-1/3">
                        <h2 class="text-3xl font-black text-slate-800 tracking-tight mb-4">Cara Memulai Pendaftaran</h2>
                        <p class="text-slate-500 text-lg mb-8 leading-relaxed">Proses pendaftaran beasiswa di Fakultas Teknik sangat transparan dan mudah diikuti. Siapkan dokumen Anda dan ikuti langkah berikut.</p>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-colors">
                                Lanjut ke Dashboard <span aria-hidden="true">&rarr;</span>
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition-colors">
                                Daftar Sekarang <span aria-hidden="true">&rarr;</span>
                            </a>
                        @endauth
                    </div>
                    
                    <div class="w-full lg:w-2/3">
                        <div class="grid sm:grid-cols-2 gap-8 relative">
                            {{-- Step 1 --}}
                            <div class="relative bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                <div class="absolute -top-4 -left-4 w-10 h-10 bg-blue-600 text-white font-black rounded-full flex items-center justify-center shadow-lg shadow-blue-500/30 text-lg border-4 border-white">1</div>
                                <h4 class="text-lg font-bold text-slate-800 mb-2 mt-2">Buat Akun & Profil</h4>
                                <p class="text-sm text-slate-500">Mendaftar menggunakan email aktif, lalu lengkapi data profil mahasiswa Anda seperti NPM dan Program Studi.</p>
                            </div>
                            
                            {{-- Step 2 --}}
                            <div class="relative bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                <div class="absolute -top-4 -left-4 w-10 h-10 bg-blue-600 text-white font-black rounded-full flex items-center justify-center shadow-lg shadow-blue-500/30 text-lg border-4 border-white">2</div>
                                <h4 class="text-lg font-bold text-slate-800 mb-2 mt-2">Pilih Program Beasiswa</h4>
                                <p class="text-sm text-slate-500">Telusuri daftar program beasiswa yang sedang dibuka dan pilih yang sesuai dengan kualifikasi Anda.</p>
                            </div>
                            
                            {{-- Step 3 --}}
                            <div class="relative bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                <div class="absolute -top-4 -left-4 w-10 h-10 bg-blue-600 text-white font-black rounded-full flex items-center justify-center shadow-lg shadow-blue-500/30 text-lg border-4 border-white">3</div>
                                <h4 class="text-lg font-bold text-slate-800 mb-2 mt-2">Lengkapi Persyaratan</h4>
                                <p class="text-sm text-slate-500">Unggah dokumen persyaratan yang diminta seperti KHS, KTP, dan sertifikat pendukung lainnya sesuai format.</p>
                            </div>
                            
                            {{-- Step 4 --}}
                            <div class="relative bg-slate-50 p-6 rounded-2xl border border-slate-100">
                                <div class="absolute -top-4 -left-4 w-10 h-10 bg-blue-600 text-white font-black rounded-full flex items-center justify-center shadow-lg shadow-blue-500/30 text-lg border-4 border-white">4</div>
                                <h4 class="text-lg font-bold text-slate-800 mb-2 mt-2">Seleksi & Pengumuman</h4>
                                <p class="text-sm text-slate-500">Pantau terus status validasi dan jadwal seleksi wawancara langsung melalui dashboard akun Anda.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Program Studi Section --}}
            <section id="program-studi" class="py-16 border-t border-slate-200/60">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Program Studi </h2>
                    <p class="text-slate-500 mt-2 max-w-2xl mx-auto">Tiga program studi Fakultas Teknik Universitas Suryakancana yang mencetak lulusan kompeten.</p>
                </div>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/30 text-center hover:-translate-y-1 transition-transform">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Teknik Informatika</h3>
                        <p class="text-sm text-slate-500">Mencetak tenaga ahli di bidang rekayasa perangkat lunak, sistem cerdas, dan jaringan komputer.</p>
                    </div>
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/30 text-center hover:-translate-y-1 transition-transform">
                        <div class="w-16 h-16 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-600 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Teknik Sipil</h3>
                        <p class="text-sm text-slate-500">Berfokus pada perancangan, pembangunan, dan pemeliharaan infrastruktur dan lingkungan fisik.</p>
                    </div>
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-lg shadow-slate-200/30 text-center hover:-translate-y-1 transition-transform">
                        <div class="w-16 h-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800 mb-3">Teknik Industri</h3>
                        <p class="text-sm text-slate-500">Mempelajari optimasi sistem terintegrasi yang mencakup manusia, material, peralatan, dan energi.</p>
                    </div>
                </div>
            </section>

            {{-- FAQ Section --}}
            <section id="faq" class="py-16 border-t border-slate-200/60">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-black text-slate-800 tracking-tight">Pertanyaan Umum (FAQ)</h2>
                    <p class="text-slate-500 mt-2 max-w-2xl mx-auto">Jawaban cepat untuk pertanyaan yang sering diajukan mengenai program beasiswa Fakultas Teknik.</p>
                </div>
                <div class="max-w-3xl mx-auto space-y-4 px-4">
                    {{-- FAQ 1 --}}
                    <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-100 shadow-sm transition-all duration-200 hover:border-blue-200">
                        <button @click="open = !open" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-2xl">
                            <h4 class="text-lg font-bold text-slate-800 pr-4" :class="{ 'text-blue-600': open }">Siapa saja yang berhak mendaftar beasiswa ini?</h4>
                            <span class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center shrink-0 transition-transform duration-300" :class="{ 'rotate-180 bg-blue-50 text-blue-600': open }">
                                <svg class="w-5 h-5 text-slate-400" :class="{ 'text-blue-600': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="px-6 pb-6 text-slate-500 leading-relaxed">
                            Seluruh mahasiswa aktif Fakultas Teknik Universitas Suryakancana (Prodi Informatika, Sipil, dan Industri) yang memenuhi syarat IPK minimum dan tidak sedang menerima beasiswa dari pihak lain.
                        </div>
                    </div>

                    {{-- FAQ 2 --}}
                    <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-100 shadow-sm transition-all duration-200 hover:border-blue-200">
                        <button @click="open = !open" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-2xl">
                            <h4 class="text-lg font-bold text-slate-800 pr-4" :class="{ 'text-blue-600': open }">Dokumen apa saja yang wajib disiapkan?</h4>
                            <span class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center shrink-0 transition-transform duration-300" :class="{ 'rotate-180 bg-blue-50 text-blue-600': open }">
                                <svg class="w-5 h-5 text-slate-400" :class="{ 'text-blue-600': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="px-6 pb-6 text-slate-500 leading-relaxed">
                            Umumnya Anda perlu menyiapkan scan KTP, KTM, Kartu Keluarga, Transkrip Nilai (KHS) terakhir yang disahkan, pas foto terbaru, serta sertifikat prestasi pendukung (jika ada) dalam format PDF.
                        </div>
                    </div>

                    {{-- FAQ 3 --}}
                    <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-100 shadow-sm transition-all duration-200 hover:border-blue-200">
                        <button @click="open = !open" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-2xl">
                            <h4 class="text-lg font-bold text-slate-800 pr-4" :class="{ 'text-blue-600': open }">Bagaimana cara mengetahui pengumuman lolos seleksi?</h4>
                            <span class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center shrink-0 transition-transform duration-300" :class="{ 'rotate-180 bg-blue-50 text-blue-600': open }">
                                <svg class="w-5 h-5 text-slate-400" :class="{ 'text-blue-600': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="px-6 pb-6 text-slate-500 leading-relaxed">
                            Hasil seleksi tiap tahapan akan diumumkan secara langsung melalui Dashboard Akun masing-masing pendaftar, serta akan ada pemberitahuan resmi di halaman Berita & Pengumuman pada sistem ini.
                        </div>
                    </div>
                    
                    {{-- FAQ 4 --}}
                    <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-100 shadow-sm transition-all duration-200 hover:border-blue-200">
                        <button @click="open = !open" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-2xl">
                            <h4 class="text-lg font-bold text-slate-800 pr-4" :class="{ 'text-blue-600': open }">Kapan periode pendaftaran beasiswa biasanya dibuka?</h4>
                            <span class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center shrink-0 transition-transform duration-300" :class="{ 'rotate-180 bg-blue-50 text-blue-600': open }">
                                <svg class="w-5 h-5 text-slate-400" :class="{ 'text-blue-600': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="px-6 pb-6 text-slate-500 leading-relaxed">
                            Pendaftaran beasiswa umumnya dibuka setiap awal semester gasal dan genap. Pastikan Anda rajin memantau menu Berita & Pengumuman agar tidak tertinggal informasi pembukaan program.
                        </div>
                    </div>
                    
                    {{-- FAQ 5 --}}
                    <div x-data="{ open: false }" class="bg-white rounded-2xl border border-slate-100 shadow-sm transition-all duration-200 hover:border-blue-200">
                        <button @click="open = !open" class="w-full text-left px-6 py-5 flex justify-between items-center focus:outline-none focus:ring-2 focus:ring-blue-500/20 rounded-2xl">
                            <h4 class="text-lg font-bold text-slate-800 pr-4" :class="{ 'text-blue-600': open }">Apakah beasiswa ini menanggung penuh biaya UKT (Uang Kuliah Tunggal)?</h4>
                            <span class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center shrink-0 transition-transform duration-300" :class="{ 'rotate-180 bg-blue-50 text-blue-600': open }">
                                <svg class="w-5 h-5 text-slate-400" :class="{ 'text-blue-600': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </span>
                        </button>
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-2"
                             class="px-6 pb-6 text-slate-500 leading-relaxed">
                            Cakupan beasiswa bervariasi bergantung pada program yang Anda ikuti. Beberapa program memberikan potongan sebagian UKT, sementara program prestasi tertentu dapat membebaskan biaya UKT sepenuhnya.
                        </div>
                    </div>
                </div>
            </section>

            {{-- Policy Sections (Syarat & Kebijakan)
            <section id="kebijakan-privasi" class="py-16 border-t border-slate-200/60 grid md:grid-cols-2 gap-12">
                <div id="syarat-ketentuan">
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-4">Syarat & Ketentuan</h3>
                    <div class="prose prose-slate text-sm text-slate-500">
                        <p>Dengan menggunakan sistem seleksi beasiswa ini, Anda menyetujui bahwa semua data dan dokumen yang Anda unggah adalah benar, sah, dan dapat dipertanggungjawabkan keasliannya.</p>
                        <p>Fakultas Teknik berhak mendiskualifikasi pelamar secara sepihak jika ditemukan indikasi pemalsuan data atau manipulasi dokumen kapanpun selama proses seleksi berlangsung.</p>
                    </div>
                </div>
                <div>
                    <h3 class="text-2xl font-black text-slate-800 tracking-tight mb-4">Kebijakan Privasi</h3>
                    <div class="prose prose-slate text-sm text-slate-500">
                        <p>Fakultas Teknik Universitas Suryakancana berkomitmen melindungi privasi data pribadi Anda. Data yang dikumpulkan (seperti KTP, KK, Nilai) semata-mata digunakan untuk keperluan seleksi beasiswa terpadu.</p>
                        <p>Kami tidak akan membagikan, menjual, atau mendistribusikan data pribadi mahasiswa kepada pihak ketiga di luar tim verifikator beasiswa tanpa izin tertulis dari mahasiswa yang bersangkutan.</p>
                    </div>
                </div>
            </section> --}}

           
        </div>

        {{-- Footer --}}
        <x-public-footer />
    </body>
</html>
