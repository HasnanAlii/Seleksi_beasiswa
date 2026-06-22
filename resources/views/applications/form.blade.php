<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-extrabold text-2xl text-gray-800 leading-tight tracking-tight">
                {{ $application->exists ? 'Ubah Pendaftaran' : 'Tambah Pendaftaran' }}
            </h2>
            <nav class="flex text-sm font-medium text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-blue-600 cursor-pointer transition">Dashboard</a>
                <span class="mx-2">/</span>
                <a href="{{ route('applications.index') }}" class="hover:text-blue-600 cursor-pointer transition">Pendaftaran</a>
                <span class="mx-2">/</span>
                <span class="text-blue-600">{{ $application->exists ? 'Ubah' : 'Tambah' }}</span>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                            </div>
                            <div>
                                <h3 class="text-3xl font-black text-slate-800 tracking-tight">Pendaftaran Beasiswa</h3>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Formulir Pengajuan Data</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ $action }}" method="POST" enctype="multipart/form-data" data-ajax-form
                        x-data="applicationRequirementForm({
                            selectedScholarshipId: '{{ old('scholarship_id', $application->scholarship_id) }}',
                            scholarshipRequirements: @js($scholarshipRequirements ?? []),
                            existingRequirementValues: @js(old('requirement_values', $existingRequirementValues ?? [])),
                            initialValues: @js(collect($existingRequirementValues ?? [])->mapWithKeys(fn($r) => [(string)$r['requirement_id'] => $r['applicant_value'] ?? ''])->all()),
                            initialDocuments: @js(collect($existingRequirementValues ?? [])->filter(fn($r) => !empty($r['document_path']))->mapWithKeys(fn($r) => [(string)$r['requirement_id'] => $r['document_path']])->all()),
                            initialValidationStatuses: @js(collect($existingRequirementValues ?? [])->mapWithKeys(fn($r) => [(string)$r['requirement_id'] => $r['validation_status'] ?? 0])->all()),
                            initialValidationNotes: @js(collect($existingRequirementValues ?? [])->mapWithKeys(fn($r) => [(string)$r['requirement_id'] => $r['validation_notes'] ?? ''])->all()),
                            isEdit: {{ $application->exists ? 'true' : 'false' }},
                        })"
                        x-init="initialize()"
                        @submit="clearDraft()">
                        @csrf
                        @if($method === 'PUT')
                            @method('PUT')
                        @endif

                        <div class="space-y-6">

                            @if(!$application->exists)
                            {{-- === INPUT MAHASISWA BARU (selalu tampil saat create) === --}}
                            <input type="hidden" name="is_new_student" value="1">
                            <div class="space-y-5 rounded-2xl border border-blue-100 bg-blue-50 p-6 shadow-sm shadow-blue-100/50">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
                                    </svg>
                                    <h4 class="text-sm font-bold text-slate-700">Data Mahasiswa</h4>
                                    <span class="ml-auto inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full">
                                        <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                        Akun login otomatis dibuat
                                    </span>
                                </div>

                                {{-- Nama --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Nama Lengkap <span class="text-rose-500">*</span></label>
                                    <input type="text" name="new_student_name" required
                                        x-model="formData.new_student_name"
                                        placeholder="Masukkan nama lengkap"
                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('new_student_name') border-rose-500 @enderror">
                                    @error('new_student_name')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- NPM --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">NPM (Nomor Pokok Mahasiswa) <span class="text-rose-500">*</span></label>
                                    <input type="text" name="new_student_number" required
                                        x-model="formData.new_student_number"
                                        placeholder="Masukkan nomor pokok mahasiswa"
                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm font-mono focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('new_student_number') border-rose-500 @enderror">
                                    @error('new_student_number')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1.5 text-xs text-slate-400">NPM akan digunakan sebagai <strong>username</strong> dan <strong>password</strong> untuk login.</p>
                                </div>

                                {{-- Program Studi --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Program Studi <span class="text-rose-500">*</span></label>
                                    <input type="text" name="new_student_study_program" required
                                        x-model="formData.new_student_study_program"
                                        placeholder="Masukkan program studi"
                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('new_student_study_program') border-rose-500 @enderror">
                                    @error('new_student_study_program')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- Email (opsional) --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Email <span class="text-slate-400 text-xs font-normal">(opsional, untuk login)</span></label>
                                    <input type="email" name="new_student_email"
                                        x-model="formData.new_student_email"
                                        placeholder="Masukkan alamat email"
                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('new_student_email') border-rose-500 @enderror">
                                    @error('new_student_email')
                                        <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1.5 text-xs text-slate-400">Jika kosong, email otomatis diisi: <code class="bg-slate-100 px-1 rounded">npm@student.ac.id</code></p>
                                </div>
                            </div>

                            @else
                            {{-- === PILIH MAHASISWA (saat edit) === --}}
                            <div>
                                <label for="student_id" class="block text-sm font-semibold text-slate-700 mb-2">Mahasiswa <span class="text-rose-500">*</span></label>
                                <x-searchable-dropdown 
                                    name="student_id" 
                                    id="student_id" 
                                    placeholder="Pilih Mahasiswa"
                                    :options="$students->map(fn($s) => ['id' => $s->id, 'name' => $s->student_number . ' - ' . $s->name . ' (' . $s->study_program . ')'])"
                                    :value="old('student_id', $application->student_id)"
                                    :showFooter="false"
                                />
                                @error('student_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif

                            {{-- Scholarship --}}
                            <div>
                                <label for="scholarship_id" class="block text-sm font-semibold text-slate-700 mb-2">Beasiswa <span class="text-rose-500">*</span></label>
                                <x-searchable-dropdown 
                                    name="scholarship_id" 
                                    id="scholarship_id" 
                                    placeholder="Pilih Beasiswa"
                                    :options="$scholarships->map(fn($s) => ['id' => $s->id, 'name' => $s->scholarship_name])"
                                    :value="old('scholarship_id', $application->scholarship_id)"
                                    :showFooter="false"
                                    x-model="selectedScholarshipId"
                                />
                                @error('scholarship_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Persyaratan Beasiswa & Input Nilai Pendaftar --}}
                            <div class="space-y-5 rounded-2xl border border-blue-100 bg-blue-50/40 p-6" x-show="selectedScholarshipId" x-cloak>
                                <div class="flex items-center gap-2 mb-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                                    </svg>
                                    <div>
                                        <h4 class="text-sm font-bold text-slate-700">Persyaratan Beasiswa</h4>
                                        <p class="text-[11px] text-slate-500">Isi nilai/kondisi pendaftar sesuai ketentuan pada setiap syarat.</p>
                                    </div>
                                </div>

                                <template x-if="selectedRequirements.length === 0">
                                    <p class="text-sm text-slate-500">Beasiswa ini belum memiliki data persyaratan.</p>
                                </template>

                                <div class="space-y-4" x-show="selectedRequirements.length > 0">
                                    <template x-for="(requirement, index) in selectedRequirements" :key="requirement.requirement_id">
                                        <div class="rounded-xl bg-white border border-slate-200 p-5 shadow-sm hover:border-blue-300 hover:shadow-md transition-all duration-200">
                                            <input type="hidden" :name="`requirement_values[${index}][requirement_id]`" :value="requirement.requirement_id">
                                            <input type="hidden" :name="`requirement_values[${index}][term]`" :value="requirement.term || ''">

                                            <div class="flex items-center justify-between gap-3 mb-4">
                                                <div class="flex items-center gap-2">
                                                    <div class="p-1.5 bg-blue-50 rounded-lg">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <h5 class="text-sm font-bold text-slate-800" x-text="requirement.requirement_name"></h5>
                                                </div>
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">
                                                    Syarat
                                                </span>
                                            </div>

                                            <div class="rounded-xl bg-slate-50 border border-slate-100 px-4 py-3 mb-4">
                                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Ketentuan Utama</p>
                                                <p class="text-[13px] font-medium text-slate-600 leading-relaxed" x-text="requirement.term || 'Tidak ada ketentuan khusus' "></p>
                                            </div>

                                            {{-- Nilai & Dokumen --}}
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                {{-- Nilai Data Pendaftar --}}
                                                <div>
                                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2"> Data Pendaftar</label>
                                                    <input type="text"
                                                        :name="`requirement_values[${index}][applicant_value]`"
                                                        x-model="valuesByRequirement[requirement.requirement_id]"
                                                        placeholder="Masukkan data pendaftar"
                                                        class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all">
                                                </div>

                                                {{-- Upload Dokumen --}}
                                                <div
                                                    x-data="{
                                                        fileName: null,
                                                        fileSize: null,
                                                        isDragging: false,
                                                        existingDoc: documentsByRequirement[requirement.requirement_id] ?? null,
                                                        handleFile(file) {
                                                            if (!file) return;
                                                            this.fileName = file.name;
                                                            this.fileSize = (file.size / 1024).toFixed(1) + ' KB';
                                                        }
                                                    }"
                                                >
                                                    <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2">Dokumen</label>

                                                    {{-- Tampilan dokumen lama (read-only, seperti show) --}}
                                                    <template x-if="existingDoc">
                                                        <div class="mb-2.5 space-y-1.5">
                                                            {{-- <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dokumen saat ini</p> --}}
                                                            <a :href="`/storage/${existingDoc}`" target="_blank"
                                                                class="inline-flex items-center gap-2 px-6 py-3.5 rounded-lg bg-blue-50 border border-blue-100 text-blue-700 hover:bg-blue-100 hover:border-blue-200 transition-all font-semibold text-xs group">
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                </svg>
                                                                <span x-text="existingDoc.split('/').pop()" class="max-w-[150px] truncate"></span>
                                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0 opacity-50 group-hover:opacity-100 transition-opacity" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                                </svg>
                                                            </a>
                                                        </div>
                                                    </template>

                                                    {{-- Area upload — di-comment sementara (tidak perlu ganti dokumen di edit) --}}
                                                    {{-- <label
                                                        :for="`doc_upload_${requirement.requirement_id}`"
                                                        @dragover.prevent="isDragging = true"
                                                        @dragleave.prevent="isDragging = false"
                                                        @drop.prevent="isDragging = false; handleFile($event.dataTransfer.files[0]);"
                                                        :class="isDragging ? 'border-blue-400 bg-blue-50' : 'border-slate-200 bg-white hover:border-blue-300 hover:bg-blue-50/30'"
                                                        class="flex flex-col items-center justify-center gap-2 w-full rounded-xl border-2 border-dashed px-4 py-4 cursor-pointer transition-all duration-200 group"
                                                    >
                                                        <template x-if="!fileName">
                                                            <div class="flex flex-col items-center gap-1.5 text-center">
                                                                <div class="p-2 bg-slate-100 rounded-lg group-hover:bg-blue-100 transition-colors">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 group-hover:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                                    </svg>
                                                                </div>
                                                                <p class="text-xs text-slate-400 group-hover:text-blue-500 font-semibold transition-colors" x-text="existingDoc ? 'Klik untuk mengganti dokumen' : 'Klik atau seret file'"></p>
                                                                <p class="text-[10px] text-slate-300">PDF, JPG, PNG, DOC &mdash; maks 5 MB</p>
                                                            </div>
                                                        </template>
                                                        <template x-if="fileName">
                                                            <div class="flex items-center gap-2.5 w-full">
                                                                <div class="p-2 bg-blue-100 rounded-lg shrink-0">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                                    </svg>
                                                                </div>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-xs font-bold text-slate-700 truncate" x-text="fileName"></p>
                                                                    <p class="text-[10px] text-slate-400" x-text="fileSize"></p>
                                                                </div>
                                                                <button type="button" @click.prevent="fileName = null; fileSize = null;" class="p-1 hover:bg-rose-50 rounded-lg transition-colors" title="Batal ganti dokumen">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 text-slate-400 hover:text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </template>
                                                        <input
                                                            type="file"
                                                            :id="`doc_upload_${requirement.requirement_id}`"
                                                            :name="`requirement_documents[${requirement.requirement_id}]`"
                                                            accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                                            class="sr-only"
                                                            @change="handleFile($event.target.files[0])"
                                                        >
                                                    </label> --}}
                                                </div>

                                        @hasrole('admin|staf')
                                        @if($application->exists)
                                        {{-- Validasi Dokumen/Data --}}
                                        <div class="mt-4 pt-4 border-t border-slate-100">
                                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-2.5">Status Validasi</p>
                                            <div class="flex items-center gap-2 flex-wrap mb-3">
                                                <button type="button"
                                                    @click="validationStatusByRequirement[requirement.requirement_id] = 0"
                                                    :class="(validationStatusByRequirement[requirement.requirement_id] ?? 0) == 0 ? 'bg-slate-100 text-slate-700 ring-1 ring-slate-300' : 'bg-white text-slate-400 border border-slate-200 hover:bg-slate-50'"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                                    Belum Divalidasi
                                                </button>
                                                <button type="button"
                                                    @click="validationStatusByRequirement[requirement.requirement_id] = 1"
                                                    :class="(validationStatusByRequirement[requirement.requirement_id] ?? 0) == 1 ? 'bg-emerald-100 text-emerald-700 ring-1 ring-emerald-400' : 'bg-white text-slate-400 border border-slate-200 hover:bg-emerald-50'"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                                    ✓ Valid
                                                </button>
                                                <button type="button"
                                                    @click="validationStatusByRequirement[requirement.requirement_id] = 2"
                                                    :class="(validationStatusByRequirement[requirement.requirement_id] ?? 0) == 2 ? 'bg-rose-100 text-rose-700 ring-1 ring-rose-400' : 'bg-white text-slate-400 border border-slate-200 hover:bg-rose-50'"
                                                    class="px-3 py-1.5 rounded-lg text-xs font-bold transition-all">
                                                    ✗ Ditolak
                                                </button>
                                                <input type="hidden"
                                                    :name="`requirement_validations[${requirement.requirement_id}][status]`"
                                                    :value="validationStatusByRequirement[requirement.requirement_id] ?? 0">
                                            </div>
                                            <div x-show="(validationStatusByRequirement[requirement.requirement_id] ?? 0) > 0" x-cloak>
                                                <label class="block text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1.5">Catatan Validasi</label>
                                                <textarea
                                                    :name="`requirement_validations[${requirement.requirement_id}][notes]`"
                                                    x-model="validationNotesByRequirement[requirement.requirement_id]"
                                                    rows="2"
                                                    placeholder="Tambahkan catatan validasi (opsional)"
                                                    class="w-full rounded-xl border-slate-200 bg-white px-3 py-2.5 text-xs focus:border-blue-500 focus:ring-2 focus:ring-blue-500/10 shadow-sm transition-all resize-none">
                                                </textarea>
                                            </div>
                                        </div>
                                        @endif
                                        @endhasrole
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                @error('requirement_values')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                                @error('requirement_values.*.applicant_value')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            @hasrole('admin|staf')
                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status <span class="text-rose-500">*</span></label>
                                <x-searchable-dropdown 
                                    name="status" 
                                    id="status" 
                                    placeholder="Pilih Status"
                                    :options="[
                                        ['id' => 'diproses', 'name' => 'Diverifikasi'],
                                        ['id' => 'ditolak', 'name' => 'Ditolak'],
                                    ]"
                                    :value="old('status', in_array($application->status, ['diproses','ditolak']) ? $application->status : 'diproses')"
                                    :showFooter="false"
                                    x-model="formData.status"
                                    compact
                                />
                                @error('status')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Catatan / Deskripsi</label>
                                <textarea id="description" name="description" rows="4"
                                    x-model="formData.description"
                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('description') border-rose-500 focus:ring-rose-500/10 @enderror"
                                    placeholder="Opsional"></textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                            @endhasrole

                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-12 flex items-center justify-end gap-4 border-t border-slate-100 pt-10">
                            <a href="{{ route('applications.index') }}"
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
        function applicationRequirementForm({ selectedScholarshipId, scholarshipRequirements, existingRequirementValues, initialValues, initialDocuments, initialValidationStatuses, initialValidationNotes, isEdit }) {
            const STORAGE_KEY = 'scholarship_application_draft';

            return {
                selectedScholarshipId: selectedScholarshipId || '',
                scholarshipRequirements: scholarshipRequirements || {},
                existingRequirementValues: existingRequirementValues || [],
                valuesByRequirement: initialValues || {},
                documentsByRequirement: initialDocuments || {},
                validationStatusByRequirement: initialValidationStatuses || {},
                validationNotesByRequirement: initialValidationNotes || {},
                
                // Form Fields for Auto-Save
                formData: {
                    new_student_name: '',
                    new_student_number: '',
                    new_student_study_program: '',
                    new_student_email: '',
                    status: 'diproses',
                    description: ''
                },

                initialize() {
                    // 1. Load basic values from PHP (old input or existing record)
                    this.formData.new_student_name = '{{ old('new_student_name', $application->exists ? $application->student->name : '') }}';
                    this.formData.new_student_number = '{{ old('new_student_number', $application->exists ? $application->student->student_number : '') }}';
                    this.formData.new_student_study_program = '{{ old('new_student_study_program', $application->exists ? $application->student->study_program : '') }}';
                    this.formData.new_student_email = '{{ old('new_student_email', $application->exists ? $application->student?->user?->email : '') }}';
                    this.formData.status = '{{ old('status', in_array($application->status, ['diproses','ditolak']) ? $application->status : 'diproses') }}';
                    this.formData.description = '{{ str_replace(["\r", "\n"], ['\r', '\n'], old('description', $application->description)) }}';

                    // valuesByRequirement & documentsByRequirement sudah diisi dari initialValues/initialDocuments
                    // (dipass langsung dari PHP via x-data, tidak perlu loop di sini)

                    // 2. Restore LocalStorage HANYA untuk form CREATE (bukan edit)
                    if (!isEdit) {
                        this.restoreFromLocal();
                    }

                    // 3. Watch for changes to save automatically
                    this.$watch('formData', () => this.saveToLocal(), { deep: true });
                    this.$watch('valuesByRequirement', () => this.saveToLocal(), { deep: true });
                    this.$watch('selectedScholarshipId', () => this.saveToLocal());
                },

                saveToLocal() {
                    // Don't save if it's an edit of an existing record (optional, usually safer)
                    // if ('{{ $application->exists }}') return; 

                    const dataToSave = {
                        formData: this.formData,
                        valuesByRequirement: this.valuesByRequirement,
                        selectedScholarshipId: this.selectedScholarshipId,
                        timestamp: new Date().getTime()
                    };
                    localStorage.setItem(STORAGE_KEY, JSON.stringify(dataToSave));
                },

                restoreFromLocal() {
                    const saved = localStorage.getItem(STORAGE_KEY);
                    if (saved) {
                        try {
                            const data = JSON.parse(saved);
                            
                            // Only restore if data is less than 24 hours old
                            const isFresh = (new Date().getTime() - data.timestamp) < (24 * 60 * 60 * 1000);
                            
                            if (isFresh) {
                                // Overwrite basic form data with draft
                                Object.keys(data.formData).forEach(key => {
                                    if (data.formData[key]) {
                                        this.formData[key] = data.formData[key];
                                    }
                                });

                                // Overwrite requirement values with draft
                                Object.keys(data.valuesByRequirement).forEach(key => {
                                    if (data.valuesByRequirement[key]) {
                                        this.valuesByRequirement[key] = data.valuesByRequirement[key];
                                    }
                                });

                                // Restore Scholarship if draft has it
                                if (data.selectedScholarshipId) {
                                    this.selectedScholarshipId = data.selectedScholarshipId;
                                }
                            }
                        } catch (e) {
                            console.error('Failed to restore draft', e);
                        }
                    }
                },

                clearDraft() {
                    localStorage.removeItem(STORAGE_KEY);
                },

                get selectedRequirements() {
                    if (!this.selectedScholarshipId) {
                        return [];
                    }

                    return this.scholarshipRequirements[this.selectedScholarshipId] || [];
                },
            };
        }
    </script>
</x-app-layout>
