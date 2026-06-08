<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Preview Import Data
            </h2>
            <nav class="flex items-center text-sm font-medium text-slate-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('selections.index') }}" class="hover:text-blue-600 transition">Seleksi</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Import</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-[#f0f6ff] min-h-screen px-3 md:px-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-2xl shadow-sm">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-amber-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-amber-800">Review Perubahan</h3>
                        <div class="mt-2 text-sm text-amber-700">
                            <p>Sistem mendeteksi <strong>{{ $previewData->where('changed', true)->count() }} data</strong> yang mengalami perubahan status dari file Excel yang diunggah. Harap periksa kembali sebelum menyimpan secara permanen.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Data yang Akan Diperbarui</h3>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-500 border-y border-slate-200">
                            <tr>
                                <th class="px-6 py-4">Nama / NPM</th>
                                <th class="px-6 py-4">Beasiswa</th>
                                <th class="px-6 py-4 text-center">Tahap Sebelumnya</th>
                                <th class="px-6 py-4 text-center">Tahap Baru</th>
                                <th class="px-6 py-4 text-center">Status Sebelumnya</th>
                                <th class="px-6 py-4 text-center">Status Baru</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($previewData->where('changed', true) as $data)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-semibold text-slate-800">{{ $data['student_name'] }}</div>
                                    <div class="text-xs text-slate-500">{{ $data['npm'] }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $data['scholarship_name'] }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600">
                                        {{ $data['old_stage'] ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(isset($data['new_stage']) && $data['new_stage'] !== $data['old_stage'])
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-blue-100 text-blue-700">
                                        {{ $data['new_stage'] }}
                                    </span>
                                    @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-500">
                                        {{ $data['old_stage'] ?? '-' }}
                                    </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-slate-100 text-slate-600">
                                        {{ ucwords($data['old_status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-emerald-100 text-emerald-700">
                                        {{ ucwords($data['new_status']) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                    <p class="font-medium">Tidak ada perubahan status yang dideteksi.</p>
                                    <p class="text-xs mt-1">Semua status di file Excel sudah sama dengan di sistem, atau data tidak ditemukan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 bg-slate-50 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <p class="text-sm text-slate-500 font-medium">
                        Klik Konfirmasi jika data sudah benar. Status Seleksi dan Penerimaan akan otomatis diperbarui.
                    </p>
                    
                    <div class="flex gap-3">
                        <a href="{{ route('selections.index') }}" 
                           class="inline-flex justify-center items-center px-5 py-2.5 bg-white border border-slate-300 text-slate-700 text-sm font-semibold rounded-xl shadow-sm hover:bg-slate-50 hover:text-slate-900 transition-all">
                            Batal
                        </a>
                        
                        @if($previewData->where('changed', true)->count() > 0)
                        <form action="{{ route('selections.import.apply') }}" method="POST">
                            @csrf
                            <input type="hidden" name="cache_key" value="{{ $cacheKey }}">
                            <button type="submit" 
                                    class="inline-flex justify-center items-center gap-2 px-6 py-2.5 bg-violet-600 text-white text-sm font-semibold rounded-xl shadow-lg shadow-violet-500/30 hover:bg-violet-700 transition-all">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Konfirmasi Update
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>
