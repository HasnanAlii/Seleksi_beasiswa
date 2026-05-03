<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ $interview->exists ? 'Ubah Jadwal Wawancara' : 'Tambah Jadwal Wawancara' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('interviews.index') }}" class="hover:text-blue-600 cursor-pointer transition">Wawancara</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $interview->exists ? 'Ubah' : 'Tambah' }}</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f8fafc] min-h-screen px-6 lg:px-10 relative overflow-hidden">
        {{-- Decorative background elements --}}
        <div class="absolute top-0 left-0 w-full h-64 bg-gradient-to-b from-blue-50/50 to-transparent -z-10"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-blue-100/30 rounded-full blur-3xl -z-10"></div>
        <div class="absolute top-1/2 -left-24 w-72 h-72 bg-indigo-100/20 rounded-full blur-3xl -z-10"></div>

        <div class="max-w-4xl mx-auto relative">
            <div class="bg-white shadow-2xl shadow-slate-200/60 rounded-[2.5rem] border border-slate-100 overflow-hidden">
                <div class="p-10 md:p-14">
                    <div class="mb-10 border-b border-slate-100 pb-8">
                        <div class="flex items-center gap-4 mb-3">
                            <div class="p-3 bg-blue-50 rounded-2xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-3xl font-black text-slate-800 tracking-tight">Jadwal Wawancara</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Formulir Penjadwalan</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ $action }}" method="POST" data-ajax-form
                        x-data="interviewForm()"
                        x-init="initialize()"
                        @submit="clearDraft()">
                        @csrf
                        @if($method === 'PUT')
                            @method('PUT')
                        @endif

                        <div class="space-y-6">
                            {{-- Pendaftaran --}}
                            <div>
                                <label for="application_id" class="block text-sm font-semibold text-slate-700 mb-2">Pendaftaran (Mahasiswa) <span class="text-rose-500">*</span></label>
                                <x-searchable-dropdown 
                                    name="application_id" 
                                    id="application_id" 
                                    placeholder="Pilih Pendaftar"
                                    :options="$applications->map(fn($app) => ['id' => $app->id, 'name' => $app->student->name . ' — ' . $app->scholarship->scholarship_name])"
                                    :value="old('application_id', $interview->application_id)"
                                    :showFooter="false"
                                    x-model="formData.application_id"
                                />
                                @error('application_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jadwal --}}
                            <div>
                                <label for="schedule" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal & Waktu Wawancara <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" id="schedule" name="schedule" required
                                    x-model="formData.schedule"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('schedule') border-rose-500 @enderror">
                                @error('schedule')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Catatan / Keterangan</label>
                                <textarea id="description" name="description" rows="4"
                                    x-model="formData.description"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('description') border-rose-500 @enderror"
                                    placeholder="Opsional..."></textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-12 flex items-center justify-end gap-4 border-t border-slate-100 pt-10">
                            <a href="{{ route('interviews.index') }}"
                                class="inline-flex justify-center rounded-2xl bg-white px-8 py-4 text-sm font-black text-slate-600 shadow-sm ring-1 ring-inset ring-slate-200 hover:bg-slate-50 transition-all uppercase tracking-widest">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center rounded-2xl bg-blue-600 px-8 py-4 text-sm font-black text-white shadow-xl shadow-blue-600/20 hover:bg-blue-700 transition-all transform hover:-translate-y-0.5 uppercase tracking-widest">
                                {{ $submitLabel }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function interviewForm() {
            const STORAGE_KEY = 'interview_form_draft';
            return {
                formData: {
                    application_id: '',
                    schedule: '',
                    description: ''
                },

                initialize() {
                    // Load initial from PHP
                    this.formData.application_id = '{{ old('application_id', $interview->application_id) }}';
                    this.formData.schedule = '{{ old('schedule', $interview->schedule ? $interview->schedule->format('Y-m-d\TH:i') : '') }}';
                    this.formData.description = '{{ str_replace(["\r", "\n"], ['\r', '\n'], old('description', $interview->description)) }}';

                    // Restore from LocalStorage
                    this.restoreFromLocal();

                    // Watch
                    this.$watch('formData', () => this.saveToLocal(), { deep: true });
                },

                saveToLocal() {
                    const data = {
                        formData: this.formData,
                        timestamp: new Date().getTime()
                    };
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(data));
                },

                restoreFromLocal() {
                    const saved = localStorage.getItem(STORAGE_KEY);
                    if (saved) {
                        try {
                            const data = JSON.parse(saved);
                            const isFresh = (new Date().getTime() - data.timestamp) < (24 * 60 * 60 * 1000);
                            if (isFresh) {
                                Object.keys(data.formData).forEach(key => {
                                    if (data.formData[key]) {
                                        this.formData[key] = data.formData[key];
                                    }
                                });
                            }
                        } catch (e) {}
                    }
                },

                clearDraft() {
                    localStorage.removeItem(STORAGE_KEY);
                }
            }
        }
    </script>
</x-app-layout>
