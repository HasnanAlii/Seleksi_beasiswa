<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ __('Detail Pengumuman') }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('announcements.index') }}" class="hover:text-blue-600 cursor-pointer transition">Pengumuman</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Detail Data</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="mx-auto sm:px-6 lg:px-8 max-w-4xl">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl overflow-hidden border border-slate-100 p-8 lg:p-12">
                
                <div class="flex flex-col items-center text-center border-b border-slate-100 pb-8 mb-8">
                    <div class="h-16 w-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-inner">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                        </svg>
                    </div>
                    <h3 class="text-3xl font-black text-slate-800 leading-tight">{{ $announcement->title }}</h3>
                    <div class="mt-4 flex flex-wrap justify-center gap-3">
                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600">
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            {{ $announcement->date ? $announcement->date->format('d F Y') : '-' }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $announcement->publish_status ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            <div class="w-2 h-2 rounded-full {{ $announcement->publish_status ? 'bg-emerald-500' : 'bg-slate-400' }}"></div>
                            {{ $announcement->publish_status ? 'Dipublikasi' : 'Draft' }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6 bg-slate-50 rounded-2xl p-6 border border-slate-100">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-1">Terkait Beasiswa</span>
                            <p class="text-sm font-bold text-slate-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0v6" /></svg>
                                {{ $announcement->scholarship->name ?? 'Tidak Ada Data' }}
                            </p>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-1">Dibuat Pada</span>
                            <p class="text-sm font-bold text-slate-800">{{ $announcement->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-6 bg-slate-50 rounded-2xl p-6 border border-slate-100">
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-1">Terakhir Diupdate</span>
                            <p class="text-sm font-bold text-slate-800">{{ $announcement->updated_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] block mb-1">ID Data</span>
                            <p class="text-sm font-bold text-slate-500 font-mono">#{{ str_pad($announcement->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                </div>

                <div class="mt-10 pt-8 border-t border-slate-100 flex justify-center gap-4">
                    <a href="{{ route('announcements.index') }}" 
                        class="px-8 py-3 rounded-xl bg-slate-100 text-slate-600 text-sm font-bold hover:bg-slate-200 transition-colors">
                        Kembali
                    </a>
                    <a href="{{ route('announcements.edit', $announcement) }}" 
                        class="px-8 py-3 rounded-xl bg-blue-600 text-white text-sm font-bold shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:-translate-y-0.5 transition-all transform">
                        Edit Pengumuman
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
