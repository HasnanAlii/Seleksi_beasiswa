<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllModulesSeeder extends Seeder
{
    public function run(): void
    {
        // ============================================================
        // 1. SCHOLARSHIPS
        // ============================================================
        $scholarshipIds = [];
        $scholarships = [
            ['scholarship_name' => 'Beasiswa Unggulan Kemendikbud', 'scholarship_type' => 'Prestasi', 'quota' => 50, 'validity_period' => '2025-12-31'],
            ['scholarship_name' => 'Beasiswa KIP Kuliah', 'scholarship_type' => 'Ekonomi', 'quota' => 100, 'validity_period' => '2025-06-30'],
            ['scholarship_name' => 'Beasiswa LPDP S1', 'scholarship_type' => 'Riset', 'quota' => 30, 'validity_period' => '2026-03-31'],
            ['scholarship_name' => 'Beasiswa Daerah Istimewa Yogyakarta', 'scholarship_type' => 'Daerah', 'quota' => 20, 'validity_period' => '2025-09-30'],
            ['scholarship_name' => 'Beasiswa Yayasan Bakti Nusantara', 'scholarship_type' => 'Swasta', 'quota' => 15, 'validity_period' => '2026-01-31'],
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
            ['requirement_name' => 'Transkrip Nilai', 'rules' => 'IPK minimal 3.00, terbaru dari semester terakhir.'],
            ['requirement_name' => 'Surat Rekomendasi', 'rules' => 'Dari dosen wali atau kaprodi, bermaterai 10.000.'],
            ['requirement_name' => 'KTP / Kartu Mahasiswa', 'rules' => 'Masih berlaku, scan berwarna.'],
            ['requirement_name' => 'Surat Keterangan Tidak Mampu', 'rules' => 'Dikeluarkan oleh kelurahan, tidak lebih dari 3 bulan.'],
            ['requirement_name' => 'Esai Motivasi', 'rules' => 'Minimal 500 kata, format PDF.'],
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
        $studentIds = [];
        $students = [
            ['name' => 'Andi Firmansyah', 'student_number' => '2021010001', 'study_program' => 'Teknik Informatika'],
            ['name' => 'Dewi Rahayu', 'student_number' => '2021010002', 'study_program' => 'Sistem Informasi'],
            ['name' => 'Budi Santoso', 'student_number' => '2022020003', 'study_program' => 'Ilmu Komputer'],
            ['name' => 'Siti Nurhaliza', 'student_number' => '2022020004', 'study_program' => 'Teknik Elektro'],
            ['name' => 'Raka Pratama', 'student_number' => '2023030005', 'study_program' => 'Matematika'],
        ];
        foreach ($students as $data) {
            $studentIds[] = DB::table('students')->insertGetId(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ============================================================
        // 4. APPLICATIONS (Pendaftaran)
        // ============================================================
        $applicationIds = [];
        $statuses = ['menunggu', 'diproses', 'diterima', 'ditolak', 'menunggu'];
        for ($i = 0; $i < 5; $i++) {
            $applicationIds[] = DB::table('applications')->insertGetId([
                'student_id' => $studentIds[$i],
                'scholarship_id' => $scholarshipIds[$i],
                'status' => $statuses[$i],
                'description' => 'Pendaftaran ke-'.($i + 1).' untuk seleksi beasiswa tahun ajaran 2025/2026.',
                'created_at' => now()->subDays(rand(1, 30)),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 5. REQUIREMENTS (Scholarship Requirements - pivot)
        // ============================================================
        $srStatuses = ['menunggu', 'diverifikasi', 'ditolak', 'diverifikasi', 'menunggu'];
        foreach ($scholarshipIds as $i => $scholarshipId) {
            DB::table('scholarship_requirements')->insert([
                'scholarship_id' => $scholarshipId,
                'requirement_id' => $requirementIds[$i],
                'status' => $srStatuses[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 6. SELECTIONS
        // ============================================================
        $selectionStages = ['Administrasi', 'Akademik', 'Wawancara', 'Final', 'Pengumuman'];
        $selectionStatuses = ['verifikasi', 'wawancara', 'diterima', 'tidak diterima', 'verifikasi'];
        foreach ($applicationIds as $i => $applicationId) {
            DB::table('selection')->insert([
                'application_id' => $applicationId,
                'stage' => $selectionStages[$i],
                'status' => $selectionStatuses[$i],
                'notes' => 'Catatan seleksi tahap '.$selectionStages[$i].'.',
                'date' => now()->addDays($i + 7),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 7. INTERVIEWS (Jadwal Wawancara)
        // ============================================================
        $interviewIds = [];
        foreach ($applicationIds as $i => $applicationId) {
            $interviewIds[] = DB::table('interviews')->insertGetId([
                'application_id' => $applicationId,
                'schedule' => now()->addDays($i + 14)->setHour(9 + $i)->setMinute(0),
                'description' => 'Wawancara seleksi beasiswa untuk pendaftar ke-'.($i + 1).'.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ============================================================
        // 8. INTERVIEW ASSESSMENTS (Penilaian Wawancara)
        // ============================================================
        $interviewers = ['Dr. Ahmad Fauzi', 'Prof. Sari Dewi', 'Ir. Budi Prasetyo', 'Dr. Lestari Utami', 'Prof. Hendra Wijaya'];
        $scores = [87.50, 92.00, 75.00, 88.50, 95.00];
        foreach ($interviewIds as $i => $interviewId) {
            DB::table('interview_assessments')->insert([
                'interview_id' => $interviewId,
                'score' => $scores[$i],
                'notes' => 'Kandidat menunjukkan kemampuan komunikasi yang '.($scores[$i] >= 90 ? 'sangat baik' : 'baik').'.',
                'interviewer' => $interviewers[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

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
            ['criteria_name' => 'IPK'],
            ['criteria_name' => 'Penghasilan Orang Tua'],
            ['criteria_name' => 'Prestasi Akademik'],
            ['criteria_name' => 'Keaktifan Organisasi'],
            ['criteria_name' => 'Jarak Tempat Tinggal'],
        ];
        $fuzzyCriteriaIds = [];
        foreach ($fuzzyCriterias as $data) {
            $fuzzyCriteriaIds[] = DB::table('fuzzy_criteria')->insertGetId(array_merge($data, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // ============================================================
        // 12. FUZZY MEMBERSHIPS
        // ============================================================
        $membershipLabels = ['rendah', 'sedang', 'tinggi', 'rendah', 'sedang'];
        $minValues = [0, 2.0, 3.0, 0, 1000000];
        $midValues = [1.5, 2.75, 3.5, 50, 3000000];
        $maxValues = [2.0, 3.0, 4.0, 100, 5000000];
        foreach ($fuzzyCriteriaIds as $i => $criteriaId) {
            DB::table('fuzzy_memberships')->insert([
                'criteria_id' => $criteriaId,
                'label' => $membershipLabels[$i],
                'min_value' => $minValues[$i],
                'mid_value' => $midValues[$i],
                'max_value' => $maxValues[$i],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('✅ Seeder berhasil! Data untuk semua modul telah dibuat (5 data per modul).');
    }
}
