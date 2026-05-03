<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ $fuzzyCriteria->exists ? 'Ubah Kriteria Fuzzy' : 'Tambah Kriteria Fuzzy' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('fuzzy-criteria.index') }}" class="hover:text-blue-600 cursor-pointer transition">Kriteria Fuzzy</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $fuzzyCriteria->exists ? 'Ubah' : 'Tambah' }}</span>
            </nav>
        </div>
    </x-slot>

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Formulir Kriteria Fuzzy</h3>
                        <p class="text-sm text-slate-500 mt-1">Masukkan nama kriteria yang digunakan dalam metode fuzzy.</p>
                    </div>

                    <form action="{{ $action }}" method="POST" data-ajax-form
                        x-data="fuzzyCriteriaForm()"
                        x-init="initialize()"
                        @submit="clearDraft()">
                        @csrf
                        @if($method === 'PUT') @method('PUT') @endif

                        <div class="space-y-6">

                            {{-- Nama Kriteria --}}
                            <div>
                                <label for="criteria_name" class="block text-sm font-semibold text-slate-700 mb-2">
                                    Nama Kriteria <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" id="criteria_name" name="criteria_name" required
                                    x-model="formData.criteria_name"
                                    placeholder="Masukkan nama kriteria"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('criteria_name') border-rose-500 @enderror">
                                @error('criteria_name')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Info --}}
                            <div class="bg-indigo-50 border border-indigo-100 rounded-xl px-5 py-4">
                                <div class="flex items-start gap-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500 mt-0.5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-semibold text-indigo-700">Tentang Kriteria Fuzzy</p>
                                        <p class="text-xs text-indigo-500 mt-0.5">Setelah menambahkan kriteria, Anda dapat mendefinisikan fungsi keanggotaan (rendah, sedang, tinggi) melalui menu Fungsi Keanggotaan.</p>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('fuzzy-criteria.index') }}"
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
        function fuzzyCriteriaForm() {
            const STORAGE_KEY = 'fuzzy_criteria_form_draft';
            return {
                formData: {
                    criteria_name: ''
                },

                initialize() {
                    this.formData.criteria_name = '{{ old('criteria_name', $fuzzyCriteria->criteria_name) }}';
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
