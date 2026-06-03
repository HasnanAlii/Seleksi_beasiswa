<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-md border-b border-slate-200/60 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3 hover:opacity-90 transition-opacity">
                <img src="{{ asset('images/icon/logoft.png') }}" alt="Logo FT UNSUR" class="h-10 w-10 object-contain drop-shadow-md">
                <div class="flex flex-col">
                    <span class="text-xl font-black text-slate-800 tracking-tight leading-none">Beasiswa<span class="text-blue-600">FT</span></span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-0.5">Universitas Suryakancana</span>
                </div>
            </a>

            @if (Route::has('login'))
                <nav class="flex items-center gap-4 sm:gap-6">
                    <a href="{{ route('news.public_index') }}" class="hidden sm:block font-bold text-slate-600 hover:text-blue-600 transition-colors text-sm">
                        Berita & Pengumuman
                    </a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-5 py-2 bg-blue-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="font-bold text-slate-600 hover:text-blue-600 transition-colors text-sm">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2 bg-blue-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:-translate-y-0.5 transition-all">
                                Daftar Sekarang
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </div>
    </div>
</header>
