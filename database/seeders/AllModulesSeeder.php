<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllModulesSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 0. SCHOLARSHIP TYPES (Jenis Beasiswa)
        // ============================================================
        $scholarshipTypeIds = [];
        $scholarshipTypes = [
            ['name' => 'Ekonomi',    'description' => 'Beasiswa berbasis kondisi ekonomi/finansial mahasiswa'],
            ['name' => 'Prestasi',   'description' => 'Beasiswa berbasis prestasi akademik maupun non-akademik'],
            ['name' => 'Keagamaan', 'description' => 'Beasiswa berbasis kemampuan atau dedikasi di bidang keagamaan'],
        ];
        foreach ($scholarshipTypes as $data) {
            // Upsert agar tidak duplikat jika seeder dijalankan ulang
            $existing = DB::table('scholarship_types')->where('name', $data['name'])->first();
            if ($existing) {
                $scholarshipTypeIds[$data['name']] = $existing->id;
            } else {
                $scholarshipTypeIds[$data['name']] = DB::table('scholarship_types')->insertGetId(array_merge($data, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
            }
        }

        // ============================================================
        // 1. SCHOLARSHIPS
        // ============================================================
        $scholarshipIds = [];
        $scholarships = [
            [
                'scholarship_name' => 'KIP Kuliah',
                'scholarship_type_id' => $scholarshipTypeIds['Ekonomi'],
                'quota' => 100,
                'validity_period' => '2026-12-31',
            ],
            [
                'scholarship_name' => 'Beasiswa PEMDA',
                'scholarship_type_id' => $scholarshipTypeIds['Prestasi'],
                'quota' => 50,
                'validity_period' => '2026-12-31',
            ],
        ];
        foreach ($scholarships as $data) {
            $scholarshipIds[] = DB::table('scholarships')->insertGetId(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ============================================================
        // 2. REQUIREMENTS (Persyaratan)
        // ============================================================
        $requirementIds = [];
        $requirements = [
            ['requirement_name' => 'Penghasilan Orang Tua'],
            ['requirement_name' => 'Status DTKS'],
            ['requirement_name' => 'Jumlah Tanggungan Orang Tua'],
            ['requirement_name' => 'Prestasi Akademik'],
        ];
        foreach ($requirements as $data) {
            $requirementIds[] = DB::table('requirements')->insertGetId(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ============================================================
        // 3. STUDENTS (Mahasiswa)
        // ============================================================
        // $studentIds = [];
        // $students = [
        //     ['name' => 'Andi Firmansyah', 'student_number' => '2021010001', 'study_program' => 'Teknik Informatika'],
        //     ['name' => 'Dewi Rahayu', 'student_number' => '2021010002', 'study_program' => 'Sistem Informasi'],
        //     ['name' => 'Budi Santoso', 'student_number' => '2022020003', 'study_program' => 'Ilmu Komputer'],
        //     ['name' => 'Siti Nurhaliza', 'student_number' => '2022020004', 'study_program' => 'Teknik Elektro'],
        //     ['name' => 'Raka Pratama', 'student_number' => '2023030005', 'study_program' => 'Matematika'],
        // ];
        // foreach ($students as $data) {
        //     $studentIds[] = DB::table('students')->insertGetId(array_merge($data, [
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]));
        // }

        // ============================================================
        // 4. APPLICATIONS (Pendaftaran)
        // ============================================================
        // $applicationIds = [];
        // $statuses = ['menunggu', 'diproses', 'diterima', 'ditolak', 'menunggu'];
        // for ($i = 0; $i < 5; $i++) {
        //     $applicationIds[] = DB::table('applications')->insertGetId([
        //         'student_id' => $studentIds[$i],
        //         'scholarship_id' => $scholarshipIds[$i],
        //         'status' => $statuses[$i],
        //         'description' => 'Pendaftaran ke-'.($i + 1).' untuk seleksi beasiswa tahun ajaran 2025/2026.',
        //         'created_at' => now()->subDays(rand(1, 30)),
        //         'updated_at' => now(),
        //     ]);
        // }

        // ============================================================
        // 5. REQUIREMENTS (Scholarship Requirements - pivot)
        // Setiap beasiswa mendapat SEMUA persyaratan dengan ketentuan spesifik
        // ============================================================
        $requirementTerms = [

            // Hasil Wawancara

            // Penghasilan Orang Tua
            $requirementIds[0] => 'Penghasilan orang tua/wali tidak melebihi Rp 5.000.000 per bulan yang dibuktikan dengan surat keterangan penghasilan.',

            // Status DTKS
            $requirementIds[1] => 'Terdaftar dalam Data Terpadu Kesejahteraan Sosial (DTKS) yang dibuktikan dengan dokumen pendukung yang sah.',

            // Jumlah Tanggungan Orang Tua
            $requirementIds[2] => 'Memiliki minimal 2 anggota keluarga yang menjadi tanggungan orang tua/wali.',

            // Prestasi Akademik
            $requirementIds[3] => 'Memiliki minimal 1 prestasi akademik/non-akademik atau menunjukkan capaian akademik yang baik selama masa studi.',

        ];

        foreach ($scholarshipIds as $scholarshipId) {
            foreach ($requirementIds as $requirementId) {
                DB::table('scholarship_requirements')->insert([
                    'scholarship_id' => $scholarshipId,
                    'requirement_id' => $requirementId,
                    'terms' => $requirementTerms[$requirementId],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // ============================================================
        // 6. SELECTIONS
        // ============================================================
        // $selectionStages = ['Administrasi', 'Akademik', 'Wawancara', 'Final', 'Pengumuman'];
        // $selectionStatuses = ['verifikasi', 'wawancara', 'diterima', 'tidak diterima', 'verifikasi'];
        // foreach ($applicationIds as $i => $applicationId) {
        //     DB::table('selection')->insert([
        //         'application_id' => $applicationId,
        //         'stage' => $selectionStages[$i],
        //         'status' => $selectionStatuses[$i],
        //         'notes' => 'Catatan seleksi tahap '.$selectionStages[$i].'.',
        //         'date' => now()->addDays($i + 7),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // ============================================================
        // 7. INTERVIEWS (Jadwal Wawancara)
        // ============================================================
        // $interviewIds = [];
        // foreach ($applicationIds as $i => $applicationId) {
        //     $interviewIds[] = DB::table('interviews')->insertGetId([
        //         'application_id' => $applicationId,
        //         'schedule' => now()->addDays($i + 14)->setHour(9 + $i)->setMinute(0),
        //         'description' => 'Wawancara seleksi beasiswa untuk pendaftar ke-'.($i + 1).'.',
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // ============================================================
        // 8. INTERVIEW ASSESSMENTS (Penilaian Wawancara)
        // ============================================================
        // $interviewers = ['Dr. Ahmad Fauzi', 'Prof. Sari Dewi', 'Ir. Budi Prasetyo', 'Dr. Lestari Utami', 'Prof. Hendra Wijaya'];
        // $scores = [87.50, 92.00, 75.00, 88.50, 95.00];
        // foreach ($interviewIds as $i => $interviewId) {
        //     DB::table('interview_assessments')->insert([
        //         'interview_id' => $interviewId,
        //         'score' => $scores[$i],
        //         'notes' => 'Kandidat menunjukkan kemampuan komunikasi yang '.($scores[$i] >= 90 ? 'sangat baik' : 'baik').'.',
        //         'interviewer' => $interviewers[$i],
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]);
        // }

        // ============================================================
        // 9. NEWS (Berita)
        // ============================================================
        $newsTitles = [
            'Pendaftaran Beasiswa Unggulan 2025 Dibuka',
            'Hasil Seleksi Administrasi Beasiswa KIP Kuliah',
            'Jadwal Wawancara Beasiswa LPDP Diumumkan',
            'Tips Lolos Seleksi Beasiswa Daerah',
            'Pengumuman Penerima Beasiswa Yayasan Bakti Nusantara',
        ];
        $newsContents = [
            'Kemendikbud membuka pendaftaran Beasiswa Unggulan 2025. Segera siapkan dokumen persyaratan dan daftarkan diri Anda sebelum batas akhir pendaftaran.',
            'Hasil seleksi administrasi Beasiswa KIP Kuliah telah diumumkan. Peserta yang lolos diharapkan mempersiapkan diri untuk tahap selanjutnya.',
            'Jadwal wawancara untuk pelamar Beasiswa LPDP S1 telah ditetapkan. Pastikan hadir tepat waktu sesuai jadwal yang telah ditentukan.',
            'Berikut adalah tips dan strategi yang dapat membantu Anda lolos seleksi beasiswa daerah berdasarkan pengalaman penerima beasiswa sebelumnya.',
            'Yayasan Bakti Nusantara mengumumkan nama-nama penerima beasiswa periode 2025/2026. Selamat kepada seluruh penerima beasiswa!',
        ];
        foreach ($newsTitles as $i => $title) {
            DB::table('news')->insert([
                'title' => $title,
                'content' => $newsContents[$i],
                'created_at' => now()->subDays(5 - $i),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 10. ANNOUNCEMENTS (Pengumuman)
        // ============================================================
        foreach ($scholarshipIds as $i => $scholarshipId) {
            DB::table('announcements')->insert([
                'scholarship_id' => $scholarshipId,
                'title' => 'Pengumuman Resmi Beasiswa '.$scholarships[$i]['scholarship_name'],
                'date' => now()->addDays($i + 30)->toDateString(),
                'publish_status' => $i % 2 === 0 ? true : false,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 11. FUZZY CRITERIA
        // ============================================================
        $fuzzyCriterias = [
            ['criteria_name' => 'Hasil Wawancara'],
            ['criteria_name' => 'Penghasilan Orang Tua'],
            ['criteria_name' => 'Status DTKS'],
            ['criteria_name' => 'Jumlah Tanggungan Orang Tua'],
            ['criteria_name' => 'Prestasi Akademik'],
        ];

        $fuzzyCriteriaIds = [];

        foreach ($fuzzyCriterias as $data) {
            $fuzzyCriteriaIds[] = DB::table('fuzzy_criteria')->insertGetId([
                'criteria_name' => $data['criteria_name'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 12. FUZZY MEMBERSHIPS
        // ============================================================

        $membershipData = [

            // Hasil Wawancara (0 - 100)
            [
                'criteria_id' => $fuzzyCriteriaIds[0],
                'min_value' => 0,
                'mid_value' => 75,
                'max_value' => 100,
            ],

            // Penghasilan Orang Tua (Rp/Bulan)
            [
                'criteria_id' => $fuzzyCriteriaIds[1],
                'min_value' => 0,
                'mid_value' => 2000000,
                'max_value' => 5000000,
            ],

            // Status DTKS (1=kurang, 2=terdaftar/peak, 3=sangat terdaftar)
            // Nilai 0 (tidak terdaftar) dipetakan ke 3 oleh FuzzySelectionService
            [
                'criteria_id' => $fuzzyCriteriaIds[2],
                'min_value' => 1,
                'mid_value' => 2,
                'max_value' => 3,
            ],

            // Jumlah Tanggungan Orang Tua
            [
                'criteria_id' => $fuzzyCriteriaIds[3],
                'min_value' => 1,
                'mid_value' => 3,
                'max_value' => 5,
            ],

            // Prestasi Akademik (Jumlah Prestasi)
            [
                'criteria_id' => $fuzzyCriteriaIds[4],
                'min_value' => 0,
                'mid_value' => 2,
                'max_value' => 5,
            ],
        ];

        foreach ($membershipData as $membership) {
            foreach ($scholarshipIds as $scholarshipId) {
                DB::table('fuzzy_memberships')->insert([
                    'scholarship_id' => $scholarshipId,
                    'criteria_id' => $membership['criteria_id'],
                    'min_value' => $membership['min_value'],
                    'mid_value' => $membership['mid_value'],
                    'max_value' => $membership['max_value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('✅ Seeder berhasil! '.count($scholarshipIds).' beasiswa dan semua modul telah dibuat.');
    }
}
