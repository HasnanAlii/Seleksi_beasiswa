<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="font-extrabold text-2xl leading-tight tracking-tight text-gray-800">
                {{ $scholarship->exists ? 'Ubah Beasiswa' : 'Tambah Beasiswa' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="transition hover:text-blue-600">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('scholarships.index') }}" class="transition hover:text-blue-600">Beasiswa</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $scholarship->exists ? 'Ubah' : 'Tambah' }}</span>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" /></svg>
                            </div>
                            <div>
                                <h3 class="text-3xl font-black text-slate-800 tracking-tight">Data Beasiswa</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Formulir Pengaturan Program</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ $action }}" method="POST" data-ajax-form
                        x-data="scholarshipForm()"
                        x-init="initialize()"
                        @submit="clearDraft()">
                        @csrf
                        @if ($method !== 'POST')
                            @method($method)
                        @endif

                        <div class="space-y-6">

                            {{-- Nama Beasiswa --}}
                            <div>
                                <label for="scholarship_name" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Nama Beasiswa <span class="text-rose-500">*</span>
                                </label>
                                <input type="text" name="scholarship_name" id="scholarship_name"
                                    x-model="formData.scholarship_name" required
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('scholarship_name') border-rose-500 @enderror"
                                    placeholder="Masukkan nama beasiswa">
                                @error('scholarship_name')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Jenis Beasiswa --}}
                            <div>
                                <label for="scholarship_type" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Jenis Beasiswa <span class="text-rose-500">*</span>
                                </label>
                                <x-searchable-dropdown 
                                    name="scholarship_type" 
                                    id="scholarship_type" 
                                    placeholder="Pilih jenis beasiswa"
                                    :options="$scholarshipTypes->map(fn($type) => ['id' => $type->name, 'name' => $type->name])"
                                    :value="old('scholarship_type', $scholarship->scholarshipType?->name)"
                                    :showFooter="false"
                                    x-model="formData.scholarship_type"
                                />
                                @error('scholarship_type')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>



                            {{-- Persyaratan --}}
                            <div>
                                <div class="mb-2 flex items-center justify-between gap-2">
                                    <label class="block text-sm font-semibold text-slate-700">Persyaratan</label>
                                    <a href="{{ route('requirements.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                                        Kelola Persyaratan
                                    </a>
                                </div>
                                <div class="space-y-2 rounded-xl border border-slate-200 bg-slate-50/50 p-4">
                                    @forelse($requirements as $requirement)
                                        <div class="rounded-xl bg-white p-4 text-sm text-slate-700 shadow-sm border border-slate-100 hover:border-blue-200 hover:shadow-md transition-all duration-200">
                                            <label class="flex items-start gap-3">
                                                <input type="checkbox" name="requirement_ids[]" value="{{ $requirement->id }}"
                                                    x-model="formData.requirement_ids"
                                                    class="mt-0.5 h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                                <span class="font-semibold text-slate-800">{{ $requirement->requirement_name }}</span>
                                            </label>
                                            <div class="mt-3 pl-7" x-show="formData.requirement_ids.includes('{{ $requirement->id }}')">
                                                <label class="mb-1 block text-[11px] font-bold uppercase tracking-wider text-slate-400">
                                                    Ketentuan untuk syarat ini
                                                </label>
                                                <textarea name="requirement_terms[{{ $requirement->id }}]" rows="2"
                                                    x-model="formData.requirement_terms['{{ $requirement->id }}']"
                                                    placeholder="Masukkan ketentuan"
                                                    class="w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs text-slate-700 focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10"></textarea>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-sm text-slate-500">Belum ada data persyaratan. Silakan tambahkan terlebih dahulu.</p>
                                    @endforelse
                                </div>
                            </div>

                            {{-- Kuota & Periode --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-6 rounded-2xl bg-slate-50/80 border border-slate-100 shadow-inner">
                                <div>
                                    <label for="quota" class="mb-2 block text-sm font-semibold text-slate-700">
                                        Kuota Penerima <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="number" name="quota" id="quota" min="1"
                                        x-model="formData.quota" required
                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('quota') border-rose-500 @enderror"
                                        placeholder="Masukkan jumlah kuota">
                                    @error('quota')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="validity_period" class="mb-2 block text-sm font-semibold text-slate-700">
                                        Batas Akhir Pendaftaran <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" name="validity_period" id="validity_period"
                                        x-model="formData.validity_period" required
                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('validity_period') border-rose-500 @enderror">
                                    @error('validity_period')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-12 flex items-center justify-end gap-4 border-t border-slate-100 pt-10">
                            <a href="{{ route('scholarships.index') }}"
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
        function scholarshipForm() {
            const STORAGE_KEY = 'scholarship_form_draft';
            return {
                formData: {
                    scholarship_name: '',
                    scholarship_type: '',
                    quota: '',
                    validity_period: '',
                    requirement_ids: [],
                    requirement_terms: {}
                },

                initialize() {
                    // Load initial from PHP
                    this.formData.scholarship_name = '{{ old('scholarship_name', $scholarship->scholarship_name) }}';
                    this.formData.scholarship_type = '{{ old('scholarship_type', $scholarship->scholarshipType?->name) }}';
                    this.formData.quota = '{{ old('quota', $scholarship->quota) }}';
                    this.formData.validity_period = '{{ old('validity_period', $scholarship->validity_period ? $scholarship->validity_period->format('Y-m-d') : '') }}';
                    
                    this.formData.requirement_ids = @js(old('requirement_ids', $selectedRequirementIds ?? []));
                    this.formData.requirement_terms = @js(old('requirement_terms', $requirementTerms ?? []));

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
                                    if (data.formData[key] && (Array.isArray(data.formData[key]) ? data.formData[key].length > 0 : true)) {
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
