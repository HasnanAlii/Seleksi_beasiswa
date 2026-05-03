<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ $assessment->exists ? 'Ubah Penilaian' : 'Tambah Penilaian' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('interview-assessments.index') }}" class="hover:text-blue-600 cursor-pointer transition">Penilaian</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $assessment->exists ? 'Ubah' : 'Tambah' }}</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Formulir Penilaian Wawancara</h3>
                        <p class="text-sm text-slate-500 mt-1">Isi data hasil penilaian wawancara seleksi beasiswa.</p>
                    </div>

                    <form action="{{ $action }}" method="POST" data-ajax-form
                        x-data="assessmentForm()"
                        x-init="initialize()"
                        @submit="clearDraft()">
                        @csrf
                        @if($method === 'PUT')
                            @method('PUT')
                        @endif

                        <div class="space-y-6">

                            {{-- Wawancara --}}
                            <div>
                                <label for="interview_id" class="block text-sm font-semibold text-slate-700 mb-2">Pendaftar <span class="text-rose-500">*</span></label>
                                <x-searchable-dropdown 
                                    name="interview_id" 
                                    id="interview_id" 
                                    placeholder="Pilih Jadwal Wawancara"
                                    :options="$interviews->map(fn($interview) => ['id' => $interview->id, 'name' => $interview->application->student->name . ' — ' . $interview->application->scholarship->scholarship_name . ' (' . $interview->schedule->format('d/m/Y H:i') . ')'])"
                                    :value="old('interview_id', $assessment->interview_id)"
                                    :showFooter="false"
                                    x-model="formData.interview_id"
                                />
                                @error('interview_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Penilai --}}
                            <div>
                                <label for="interviewer" class="block text-sm font-semibold text-slate-700 mb-2">Nama Penilai <span class="text-rose-500">*</span></label>
                                <input type="text" id="interviewer" name="interviewer" required
                                    x-model="formData.interviewer"
                                    placeholder="Nama lengkap penilai..."
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('interviewer') border-rose-500 @enderror">
                                @error('interviewer')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Nilai --}}
                            <div>
                                <label for="score" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Nilai <span class="text-rose-500">*</span>
                                    <span class="text-xs font-normal text-slate-400 ml-1">(0 – 100)</span>
                                </label>
                                <input type="number" id="score" name="score" required step="0.01" min="0" max="999.99"
                                    x-model="formData.score"
                                    placeholder="Masukkan nilai penilaian"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('score') border-rose-500 @enderror">
                                @error('score')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label for="notes" class="block text-sm font-semibold text-slate-700 mb-2">Catatan Penilaian</label>
                                <textarea id="notes" name="notes" rows="4"
                                    x-model="formData.notes"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('notes') border-rose-500 @enderror"
                                    placeholder="Catatan atau komentar penilai (opsional)..."></textarea>
                                @error('notes')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('interview-assessments.index') }}"
                                class="inline-flex justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all transform hover:-translate-y-0.5">
                                {{ $submitLabel }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function assessmentForm() {
            const STORAGE_KEY = 'assessment_form_draft';
            return {
                formData: {
                    interview_id: '',
                    interviewer: '',
                    score: '',
                    notes: ''
                },

                initialize() {
                    // Load initial from PHP
                    this.formData.interview_id = '{{ old('interview_id', $assessment->interview_id) }}';
                    this.formData.interviewer = '{{ old('interviewer', $assessment->interviewer) }}';
                    this.formData.score = '{{ old('score', $assessment->score) }}';
                    this.formData.notes = '{{ str_replace(["\r", "\n"], ['\r', '\n'], old('notes', $assessment->notes)) }}';

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
