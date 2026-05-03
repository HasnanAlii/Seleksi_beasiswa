<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                Buat Jadwal Wawancara Otomatis
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('interviews.index') }}" class="hover:text-blue-600 cursor-pointer transition">Wawancara</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">Buat Otomatis</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Info Card --}}
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex gap-4 items-start">
                <svg class="w-5 h-5 text-blue-500 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <p class="text-sm font-semibold text-blue-800">Penjadwalan Otomatis</p>
                    <p class="text-sm text-blue-700 mt-1">
                        Sistem akan secara otomatis membagi jadwal wawancara bagi semua pendaftar yang belum terjadwal.
                        Urutan pendaftar akan diacak secara acak sebelum dibagi ke slot waktu yang tersedia.
                    </p>
                </div>
            </div>

            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Formulir Penjadwalan Otomatis</h3>
                        <p class="text-sm text-slate-500 mt-1">Tentukan rentang tanggal dan waktu, lalu sistem akan membuat jadwal untuk para pendaftar.</p>
                    </div>

                    <form action="{{ route('interviews.store') }}" method="POST" data-ajax-form>
                        @csrf

                        <div class="space-y-6">

                             {{-- Scholarship Filter --}}
                             <div>
                                 <label for="scholarship_id" class="block text-sm font-semibold text-slate-700 mb-2">
                                     Filter Beasiswa
                                     <span class="ml-1 text-xs font-normal text-slate-400">(opsional — kosongkan untuk semua beasiswa)</span>
                                 </label>
                                 <x-searchable-dropdown
                                     name="scholarship_id"
                                     id="scholarship_id"
                                     placeholder="Pilih Beasiswa"
                                     :options="$scholarships->map(fn($s) => ['id' => $s->id, 'name' => $s->scholarship_name])"
                                     :value="old('scholarship_id')"
                                     :showFooter="false"
                                 />
                                 @error('scholarship_id')
                                     <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                 @enderror
                             </div>

                            {{-- Date Range --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="date_from" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Tanggal Mulai <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" id="date_from" name="date_from" required
                                        value="{{ old('date_from') }}"
                                        min="{{ date('Y-m-d') }}"
                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('date_from') border-rose-500 @enderror">
                                    @error('date_from')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label for="date_to" class="block text-sm font-semibold text-slate-700 mb-2">
                                        Tanggal Selesai <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" id="date_to" name="date_to" required
                                        value="{{ old('date_to') }}"
                                        min="{{ date('Y-m-d') }}"
                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('date_to') border-rose-500 @enderror">
                                    @error('date_to')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Daily Time Range --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                     <label for="time_start" class="block text-sm font-semibold text-slate-700 mb-2">
                                         Jam Mulai (format 24 jam) <span class="text-rose-500">*</span>
                                     </label>
                                     <input type="text" id="time_start" name="time_start" required
                                         value="{{ old('time_start', '08:00') }}"
                                         placeholder="HH:mm (Contoh: 13:00)"
                                         pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]"
                                         class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('time_start') border-rose-500 @enderror">
                                    @error('time_start')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                     <label for="time_end" class="block text-sm font-semibold text-slate-700 mb-2">
                                         Jam Selesai (format 24 jam) <span class="text-rose-500">*</span>
                                     </label>
                                      <input type="text" id="time_end" name="time_end" required
                                          value="{{ old('time_end', '16:00') }}"
                                          placeholder="HH:mm (Contoh: 16:00)"
                                          pattern="([01]?[0-9]|2[0-3]):[0-5][0-9]"
                                          class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('time_end') border-rose-500 @enderror">
                                    @error('time_end')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Duration --}}
                            <div>
                                <label for="duration_minutes" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Durasi per Sesi Wawancara <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative">
                                     <input type="number" id="duration_minutes" name="duration_minutes" required
                                         value="{{ old('duration_minutes', 30) }}"
                                        min="15" max="480" step="15"
                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 pr-20 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('duration_minutes') border-rose-500 @enderror">
                                    <span class="absolute inset-y-0 right-4 flex items-center text-sm text-slate-400 pointer-events-none">menit</span>
                                </div>
                                <p class="mt-1.5 text-xs text-slate-400">Minimal 15 menit. Contoh: 120 = 2 jam per orang.</p>
                                @error('duration_minutes')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Slot Preview (Alpine.js) --}}
                            {{-- <div x-data="slotPreview()" x-init="init()" class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                                <p class="text-sm font-semibold text-slate-700 mb-3">Estimasi Slot Wawancara</p>
                                <div x-show="totalSlots > 0" class="flex flex-wrap gap-3">
                                    <div class="flex items-center gap-2 bg-white rounded-xl px-4 py-2.5 shadow-sm border border-slate-100">
                                        <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-sm text-slate-600"><span class="font-bold text-slate-800" x-text="totalDays"></span> hari</span>
                                    </div>
                                    <div class="flex items-center gap-2 bg-white rounded-xl px-4 py-2.5 shadow-sm border border-slate-100">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-sm text-slate-600"><span class="font-bold text-slate-800" x-text="slotsPerDay"></span> slot/hari</span>
                                    </div>
                                    <div class="flex items-center gap-2 bg-white rounded-xl px-4 py-2.5 shadow-sm border border-blue-200 bg-blue-50">
                                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        <span class="text-sm text-slate-600">Total <span class="font-bold text-blue-700" x-text="totalSlots"></span> pendaftar dapat dijadwalkan</span>
                                    </div>
                                </div>
                                <p x-show="totalSlots === 0" class="text-sm text-slate-400 italic">Isi rentang tanggal, waktu, dan durasi untuk melihat estimasi.</p>
                            </div> --}}

                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('interviews.index') }}"
                                class="inline-flex justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:-translate-y-0.5">
                                Buat Jadwal Otomatis
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function slotPreview() {
            return {
                totalSlots: 0,
                totalDays: 0,
                slotsPerDay: 0,

                init() {
                    const fields = ['date_from', 'date_to', 'time_start', 'time_end', 'duration_minutes'];
                    fields.forEach(id => {
                        const el = document.getElementById(id);
                        if (el) {
                            el.addEventListener('change', () => this.calculate());
                            el.addEventListener('input', () => this.calculate());
                        }
                    });
                    this.calculate();
                },

                calculate() {
                    const dateFrom = document.getElementById('date_from')?.value;
                    const dateTo = document.getElementById('date_to')?.value;
                    const timeStart = document.getElementById('time_start')?.value;
                    const timeEnd = document.getElementById('time_end')?.value;
                    const duration = parseInt(document.getElementById('duration_minutes')?.value);

                    if (!dateFrom || !dateTo || !timeStart || !timeEnd || !duration || duration < 15) {
                        this.totalSlots = 0;
                        this.totalDays = 0;
                        this.slotsPerDay = 0;
                        return;
                    }

                    const from = new Date(dateFrom);
                    const to = new Date(dateTo);
                    if (from > to) {
                        this.totalSlots = 0;
                        return;
                    }

                    const [sh, sm] = timeStart.split(':').map(Number);
                    const [eh, em] = timeEnd.split(':').map(Number);
                    
                    let slotsPerDay = 0;
                    let currentMin = sh * 60 + sm;
                    const endMin = eh * 60 + em;
                    const breakStart = 11 * 60 + 30; // 11:30
                    const breakEnd = 12 * 60 + 30;   // 12:30

                    while (currentMin + duration <= endMin) {
                        const currentSlotEnd = currentMin + duration;
                        // Cek apakah bersinggungan dengan istirahat
                        const isOverlap = currentMin < breakEnd && currentSlotEnd > breakStart;
                        
                        if (!isOverlap) {
                            slotsPerDay++;
                        }
                        currentMin += duration;
                    }

                    const msPerDay = 24 * 60 * 60 * 1000;
                    const days = Math.floor((to - from) / msPerDay) + 1;

                    this.totalDays = days;
                    this.slotsPerDay = slotsPerDay;
                    this.totalSlots = days * slotsPerDay;
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
