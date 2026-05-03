<nav
    class="fixed inset-y-0 left-0 w-[270px] bg-gradient-to-b from-[#03235b] via-[#0c5197] to-[#1c88da] shadow-2xl z-50 font-sans text-white overflow-y-auto flex flex-col">

    <div class="flex-1 flex flex-col">


        {{-- Header / Logo (centered & bigger) --}}
        <div class="px-6 pt-10 pb-7 flex flex-col items-center justify-center">
            <div class="bg-white p-3 rounded-2xl flex items-center justify-center shadow-lg">
                <x-application-logo class="h-24 w-24 text-[#03235b]" />
            </div>
            <span class="mt-4 text-2xl font-extrabold text-white tracking-tight">Seleksi Beasiswa</span>
        </div>
        {{-- Tombol Collapse --}}
        {{-- <div class="flex justify-center mb-2">
            <button @click="window.innerWidth < 768 ? showSidebar = false : sidebarCollapsed = !sidebarCollapsed"
                class="bg-[#f0f4f8] hover:bg-white text-[#03235b] rounded-lg p-1.5 shadow-sm transition-all w-8 h-8 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 stroke-[3px] transition-transform duration-300 collapse-icon" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div> --}}

        {{-- Menu Utama --}}
        <div class="px-5 pb-6 flex-1">

            {{-- RINGKASAN --}}
            <div class="mt-2">
                <p class="px-2 mb-2 text-xs font-extrabold text-[#74b3ea] uppercase tracking-widest">Ringkasan</p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" title="Halaman Utama"
                        class="relative flex items-center gap-3.5 px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group
                       {{ request()->routeIs('dashboard')
                           ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm'
                           : 'text-white hover:bg-white/5' }}">
                        @if (request()->routeIs('dashboard'))
                            <div
                                class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md">
                            </div>
                        @endif
                        <i data-feather="grid"
                            class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                        <span>Dashboard</span>
                    </a>
                </div>
            </div>

            {{-- MANAJEMEN BEASISWA --}}
            <div class="mt-7">
                <p class="px-2 mb-2 text-xs font-extrabold text-[#74b3ea] uppercase tracking-widest">Manajemen Beasiswa</p>
                <div class="space-y-1">

                    {{-- Beasiswa dropdown --}}
                    @php $isBeasiswaMenu = request()->routeIs('scholarships.*') || request()->routeIs('requirements.*'); @endphp
                    <div x-data="{ open: {{ $isBeasiswaMenu ? 'true' : 'false' }} }">
                        <button @click="open = !open" title="Menu Beasiswa"
                            class="w-full relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ $isBeasiswaMenu ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if ($isBeasiswaMenu)
                                <div class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md"></div>
                            @endif
                            <div class="flex items-center gap-3.5">
                                <i data-feather="award" class="w-5 h-5 {{ $isBeasiswaMenu ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Beasiswa</span>
                            </div>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-200 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-collapse style="{{ $isBeasiswaMenu ? '' : 'display: none;' }}" class="mt-1.5 space-y-1.5 pl-[52px] pr-2">
                            <li>
                                <a href="{{ route('scholarships.index') }}" title="Data Beasiswa"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('scholarships.*') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                                    Daftar Beasiswa
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('requirements.index') }}" title="Persyaratan"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('requirements.*') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                                    Persyaratan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- PENDAFTARAN --}}
                <div class="space-y-1">

                    {{-- Pendaftaran --}}
                    <div>
                        <a href="{{ route('applications.index') }}" title="Data Pendaftaran"
                            class="relative flex items-center gap-3.5 px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ request()->routeIs('applications.*') ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if (request()->routeIs('applications.*'))
                                <div class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md"></div>
                            @endif
                            <i data-feather="clipboard" class="w-5 h-5 {{ request()->routeIs('applications.*') ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                            <span>Pendaftaran</span>
                        </a>
                    </div>
                </div>

            {{-- SELEKSI & WAWANCARA --}}
      
                <div class="space-y-1">

                    {{-- Seleksi --}}
                    <div>
                        <a href="{{ route('selections.index') }}" title="Data Seleksi"
                            class="relative flex items-center gap-3.5 px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ request()->routeIs('selections.*') ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if (request()->routeIs('selections.*'))
                                <div class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md"></div>
                            @endif
                            <i data-feather="check-square" class="w-5 h-5 {{ request()->routeIs('selections.*') ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                            <span>Seleksi</span>
                        </a>
                    </div>

                    {{-- Wawancara --}}
                    @php $isInterviews = request()->routeIs('interviews.*') || request()->routeIs('interview-assessments.*'); @endphp
                    <div x-data="{ open: {{ $isInterviews ? 'true' : 'false' }} }">
                        <button @click="open = !open" title="Menu Wawancara"
                            class="w-full relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ $isInterviews ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                            @if ($isInterviews)
                                <div class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md"></div>
                            @endif
                            <div class="flex items-center gap-3.5">
                                <i data-feather="mic" class="w-5 h-5 {{ $isInterviews ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Wawancara</span>
                            </div>
                            <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-200 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <ul x-show="open" x-collapse style="{{ $isInterviews ? '' : 'display: none;' }}" class="mt-1.5 space-y-1.5 pl-[52px] pr-2">
                            <li>
                                <a href="{{ route('interviews.index') }}" title="Jadwal Wawancara"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('interviews.index') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                                    Jadwal Wawancara
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('interview-assessments.index') }}" title="Penilaian Wawancara"
                                    class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('interview-assessments.index') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                                    Penilaian
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- INFORMASI --}}
                {{-- <div class="mt-7">
                    <p class="px-2 mb-2 text-xs font-extrabold text-[#74b3ea] uppercase tracking-widest">Informasi</p> --}}
                    <div class="space-y-1">

                        {{-- Berita --}}
                        <div>
                            <a href="{{ route('news.index') }}" title="Berita"
                                class="relative flex items-center gap-3.5 px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ request()->routeIs('news.*') ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                                @if (request()->routeIs('news.*'))
                                    <div class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md"></div>
                                @endif
                                <i data-feather="rss" class="w-5 h-5 {{ request()->routeIs('news.*') ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Berita</span>
                            </a>
                        </div>

                        {{-- Pengumuman --}}
                        {{-- <div>
                            <a href="{{ route('announcements.index') }}" title="Pengumuman"
                                class="relative flex items-center gap-3.5 px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ request()->routeIs('announcements.index') ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                                @if (request()->routeIs('announcements.index'))
                                    <div class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md"></div>
                                @endif
                                <i data-feather="bell" class="w-5 h-5 {{ request()->routeIs('announcements.index') ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                <span>Pengumuman</span>
                            </a>
                        </div> --}}
                    </div>
                {{-- </div> --}}

                {{-- METODE FUZZY --}}
                <div class="mt-7">
                    <p class="px-2 mb-2 text-xs font-extrabold text-[#74b3ea] uppercase tracking-widest">Pengaturan Fuzzy</p>
                    <div class="space-y-1">

                        @php $isFuzzy = request()->routeIs('fuzzy-criteria.index') || request()->routeIs('fuzzy-memberships.index'); @endphp
                        <div x-data="{ open: {{ $isFuzzy ? 'true' : 'false' }} }">
                            <button @click="open = !open" title="Menu Fuzzy"
                                class="w-full relative flex items-center justify-between px-4 py-3.5 text-sm font-extrabold rounded-xl transition-all duration-200 group {{ $isFuzzy ? 'bg-white/10 ring-1 ring-white/20 text-white shadow-lg backdrop-blur-sm' : 'text-white hover:bg-white/5' }}">
                                @if ($isFuzzy)
                                    <div class="absolute left-0 top-1/2 w-[4px] h-6 -translate-y-1/2 bg-[#2ee0a7] shadow-[0_0_8px_rgba(46,224,167,0.8)] rounded-r-md"></div>
                                @endif
                                <div class="flex items-center gap-3.5">
                                    <i data-feather="cpu" class="w-5 h-5 {{ $isFuzzy ? 'text-white fill-[#ffffff33]' : 'text-[#87abc9] group-hover:text-white' }}"></i>
                                    <span>Fuzzy Logic</span>
                                </div>
                                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 transition-transform duration-200 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <ul x-show="open" x-collapse style="{{ $isFuzzy ? '' : 'display: none;' }}" class="mt-1.5 space-y-1.5 pl-[52px] pr-2">
                                <li>
                                    <a href="{{ route('fuzzy-criteria.index') }}" title="Kriteria Fuzzy"
                                        class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('fuzzy-criteria.index') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                                        Kriteria
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('fuzzy-memberships.index') }}" title="Keanggotaan Fuzzy"
                                        class="block px-3 py-2 text-sm font-extrabold rounded-lg transition-all duration-200 {{ request()->routeIs('fuzzy-memberships.index') ? 'text-white bg-white/10' : 'text-white/80 hover:text-white hover:bg-white/5' }}">
                                        Keanggotaan
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    {{-- BAGIAN BAWAH (Profil) --}}
    <div class="p-5 mt-auto" x-data="{ open: false }">
        <div
            class="bg-black/20 rounded-2xl shadow-inner ring-1 ring-white/10 backdrop-blur-lg transition-all duration-200">
            <button @click="open = !open" title="Profil & Pengaturan"
                class="w-full flex items-center gap-4 p-4 text-left rounded-2xl hover:bg-white/5 transition-all">
                <div
                    class="h-10 w-10 flex-shrink-0 rounded-full bg-white flex items-center justify-center text-[#03235b] font-extrabold text-lg uppercase shadow-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="overflow-hidden leading-tight flex-1">
                    <div class="font-extrabold text-white truncate text-sm">{{ Auth::user()->name }}</div>
                    <div class="text-[11px] text-white/70 truncate">{{ Auth::user()->email }}</div>
                </div>
                <svg :class="{ 'rotate-180': open }" class="w-4 h-4 text-white/50 transition-transform duration-200"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div x-show="open" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2"
                style="display: none;" class="px-4 pb-4">
                <div class="border-t border-white/10 pt-3 mt-1 grid grid-cols-2 gap-2">
                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center justify-center py-2 text-xs font-extrabold text-white bg-white/10 rounded-lg hover:bg-white/20 transition-all">
                        Profil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button
                            class="w-full py-2 text-xs font-extrabold text-white bg-rose-500/80 rounded-lg hover:bg-rose-500 transition-all shadow-sm">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>