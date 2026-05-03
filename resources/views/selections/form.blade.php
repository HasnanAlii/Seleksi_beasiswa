<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ $selection->exists ? 'Ubah Data Seleksi' : 'Tambah Data Seleksi' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('selections.index') }}" class="hover:text-blue-600 cursor-pointer transition">Seleksi</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $selection->exists ? 'Ubah' : 'Tambah' }}</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Formulir Data Seleksi</h3>
                        <p class="text-sm text-slate-500 mt-1">Isi data proses seleksi pendaftar beasiswa.</p>
                    </div>

                    <form action="{{ $action }}" method="POST" data-ajax-form
                        x-data="selectionForm()"
                        x-init="initialize()"
                        @submit="clearDraft()">
                        @csrf
                        @if($method === 'PUT') @method('PUT') @endif

                        <div class="space-y-6">

                            {{-- Pendaftaran --}}
                            <div>
                                <label for="application_id" class="block text-sm font-semibold text-slate-700 mb-2">Pendaftaran (Mahasiswa) <span class="text-rose-500">*</span></label>
                                <x-searchable-dropdown 
                                    name="application_id" 
                                    id="application_id" 
                                    placeholder="Pilih Pendaftar"
                                    :options="$applications->map(fn($app) => ['id' => $app->id, 'name' => $app->student->name . ' — ' . $app->scholarship->scholarship_name])"
                                    :value="old('application_id', $selection->application_id)"
                                    :showFooter="false"
                                    x-model="formData.application_id"
                                />
                                @error('application_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tahap Seleksi --}}
                            <div>
                                <label for="stage" class="block text-sm font-semibold text-slate-700 mb-2">Tahap Seleksi <span class="text-rose-500">*</span></label>
                                <input type="text" id="stage" name="stage" required
                                    x-model="formData.stage"
                                    placeholder="Masukkan tahap seleksi"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('stage') border-rose-500 @enderror">
                                @error('stage')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status <span class="text-rose-500">*</span></label>
                                <x-searchable-dropdown 
                                    name="status" 
                                    id="status" 
                                    placeholder="Pilih Status"
                                    :options="[
                                        ['id' => 'verifikasi', 'name' => 'Verifikasi'],
                                        ['id' => 'wawancara', 'name' => 'Wawancara'],
                                        ['id' => 'diterima', 'name' => 'Diterima'],
                                        ['id' => 'tidak diterima', 'name' => 'Tidak Diterima'],
                                    ]"
                                    :value="old('status', $selection->status)"
                                    :showFooter="false"
                                    x-model="formData.status"
                                    compact
                                />
                                @error('status')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Tanggal --}}
                            <div>
                                <label for="date" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Seleksi <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" id="date" name="date" required
                                    x-model="formData.date"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('date') border-rose-500 @enderror">
                                @error('date')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Catatan --}}
                            <div>
                                <label for="notes" class="block text-sm font-semibold text-slate-700 mb-2">Catatan</label>
                                <textarea id="notes" name="notes" rows="4"
                                    x-model="formData.notes"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('notes') border-rose-500 @enderror"
                                    placeholder="Catatan atau keterangan seleksi (opsional)..."></textarea>
                                @error('notes')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('selections.index') }}"
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
        function selectionForm() {
            const STORAGE_KEY = 'selection_form_draft';
            return {
                formData: {
                    application_id: '',
                    stage: '',
                    status: '',
                    date: '',
                    notes: ''
                },

                initialize() {
                    this.formData.application_id = '{{ old('application_id', $selection->application_id) }}';
                    this.formData.stage = '{{ old('stage', $selection->stage) }}';
                    this.formData.status = '{{ old('status', $selection->status) }}';
                    this.formData.date = '{{ old('date', $selection->date ? \Carbon\Carbon::parse($selection->date)->format('Y-m-d\TH:i') : '') }}';
                    this.formData.notes = '{{ str_replace(["\r", "\n"], ['\r', '\n'], old('notes', $selection->notes)) }}';

                    this.restoreFromLocal();
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
