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

    <div class="py-12 bg-[#f0f6ff] min-h-screen px-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl shadow-slate-200/60 rounded-3xl border border-slate-100 overflow-hidden">
                <div class="p-8 md:p-12">
                    <div class="mb-8 border-b border-slate-100 pb-6">
                        <h3 class="text-2xl font-bold text-slate-800">Formulir Pendaftaran Beasiswa</h3>
                        <p class="text-sm text-slate-500 mt-1">Lengkapi data pendaftaran mahasiswa di bawah ini.</p>
                    </div>

                    <form action="{{ $action }}" method="POST"
                        x-data="applicationRequirementForm({
                            selectedScholarshipId: '{{ old('scholarship_id', $application->scholarship_id) }}',
                            scholarshipRequirements: @js($scholarshipRequirements ?? []),
                            existingRequirementValues: @js(old('requirement_values', $existingRequirementValues ?? [])),
                        })"
                        x-init="initialize()">
                        @csrf
                        @if($method === 'PUT')
                            @method('PUT')
                        @endif

                        <div class="space-y-6">

                            @if(!$application->exists)
                            {{-- === INPUT MAHASISWA BARU (selalu tampil saat create) === --}}
                            <input type="hidden" name="is_new_student" value="1">
                            <div class="space-y-5 rounded-2xl border border-blue-100 bg-blue-50/40 p-6">
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
                                        value="{{ old('new_student_name') }}"
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
                                        value="{{ old('new_student_number') }}"
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
                                        value="{{ old('new_student_study_program') }}"
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
                                        value="{{ old('new_student_email') }}"
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
                                <select id="student_id" name="student_id" required
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('student_id') border-rose-500 focus:ring-rose-500/10 @enderror">
                                    <option value="">-- Pilih Mahasiswa --</option>
                                    @foreach($students as $student)
                                        <option value="{{ $student->id }}" {{ old('student_id', $application->student_id) == $student->id ? 'selected' : '' }}>
                                            {{ $student->student_number }} - {{ $student->name }} ({{ $student->study_program }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('student_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>
                            @endif

                            {{-- Scholarship --}}
                            <div>
                                <label for="scholarship_id" class="block text-sm font-semibold text-slate-700 mb-2">Beasiswa <span class="text-rose-500">*</span></label>
                                <select id="scholarship_id" name="scholarship_id" required x-model="selectedScholarshipId"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('scholarship_id') border-rose-500 focus:ring-rose-500/10 @enderror">
                                    <option value="">-- Pilih Beasiswa --</option>
                                    @foreach($scholarships as $scholarship)
                                        <option value="{{ $scholarship->id }}" {{ old('scholarship_id', $application->scholarship_id) == $scholarship->id ? 'selected' : '' }}>
                                            {{ $scholarship->scholarship_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('scholarship_id')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Persyaratan Beasiswa & Input Nilai Pendaftar --}}
                            <div class="rounded-2xl border border-slate-200 bg-slate-50/60 p-5" x-show="selectedScholarshipId" x-cloak>
                                <div class="mb-4">
                                    <h4 class="text-sm font-bold text-slate-800">Persyaratan Beasiswa</h4>
                                    <p class="text-xs text-slate-500 mt-1">Isi nilai/kondisi pendaftar sesuai ketentuan pada setiap syarat.</p>
                                </div>

                                <template x-if="selectedRequirements.length === 0">
                                    <p class="text-sm text-slate-500">Beasiswa ini belum memiliki data persyaratan.</p>
                                </template>

                                <div class="space-y-4" x-show="selectedRequirements.length > 0">
                                    <template x-for="(requirement, index) in selectedRequirements" :key="requirement.requirement_id">
                                        <div class="rounded-xl bg-white border border-slate-200 p-4">
                                            <input type="hidden" :name="`requirement_values[${index}][requirement_id]`" :value="requirement.requirement_id">
                                            <input type="hidden" :name="`requirement_values[${index}][term]`" :value="requirement.term || ''">

                                            <div class="flex items-center justify-between gap-3 mb-2">
                                                <h5 class="text-sm font-bold text-slate-800" x-text="requirement.requirement_name"></h5>
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wider bg-blue-100 text-blue-700 px-2 py-0.5 rounded-full">
                                                    <div class="w-1.5 h-1.5 rounded-full bg-blue-500"></div>
                                                    Syarat
                                                </span>
                                            </div>

                                            <div class="rounded-lg bg-slate-50 border border-slate-100 px-3 py-2 mb-3">
                                                <p class="text-[11px] font-bold uppercase tracking-wide text-slate-400 mb-1">Ketentuan</p>
                                                <p class="text-sm text-slate-700" x-text="requirement.term || 'Tidak ada ketentuan khusus' "></p>
                                            </div>

                                            <div>
                                                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1">Nilai Data Pendaftar</label>
                                                <input type="text"
                                                    :name="`requirement_values[${index}][applicant_value]`"
                                                    x-model="valuesByRequirement[requirement.requirement_id]"
                                                    placeholder="Masukkan nilai data pendaftar"
                                                    class="w-full rounded-xl border-slate-200 bg-white px-4 py-2.5 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all">
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

                            {{-- Status --}}
                            <div>
                                <label for="status" class="block text-sm font-semibold text-slate-700 mb-2">Status <span class="text-rose-500">*</span></label>
                                <select id="status" name="status" required
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('status') border-rose-500 focus:ring-rose-500/10 @enderror">
                                    {{-- <option value="menunggu" {{ old('status', $application->status) == 'menunggu' ? 'selected' : '' }}>Menunggu</option> --}}
                                    <option value="diproses" {{ old('status', $application->status) == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    {{-- <option value="diterima" {{ old('status', $application->status) == 'diterima' ? 'selected' : '' }}>Diterima</option> --}}
                                    <option value="ditolak" {{ old('status', $application->status) == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                                @error('status')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="description" class="block text-sm font-semibold text-slate-700 mb-2">Catatan / Deskripsi</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 px-4 py-3 text-sm focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 shadow-sm transition-all @error('description') border-rose-500 focus:ring-rose-500/10 @enderror"
                                    placeholder="Opsional">{{ old('description', $application->description) }}</textarea>
                                @error('description')
                                    <p class="mt-2 text-sm text-rose-500">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>

                        {{-- Action Buttons --}}
                        <div class="mt-10 flex items-center justify-end gap-4 border-t border-slate-100 pt-6">
                            <a href="{{ route('applications.index') }}"
                                class="inline-flex justify-center rounded-xl bg-white px-6 py-3 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 hover:text-slate-900 transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                class="inline-flex justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 hover:bg-blue-700 hover:shadow-blue-600/40 transition-all transform hover:-translate-y-0.5">
                                {{ $submitLabel }}
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function applicationRequirementForm({ selectedScholarshipId, scholarshipRequirements, existingRequirementValues }) {
            return {
                selectedScholarshipId: selectedScholarshipId || '',
                scholarshipRequirements: scholarshipRequirements || {},
                existingRequirementValues: existingRequirementValues || [],
                valuesByRequirement: {},

                initialize() {
                    this.existingRequirementValues.forEach((item) => {
                        const requirementId = String(item.requirement_id);
                        this.valuesByRequirement[requirementId] = item.applicant_value ?? '';
                    });
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
