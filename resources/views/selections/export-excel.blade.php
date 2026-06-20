<table>
    @php
        /**
         * Helper: ambil nilai numerik dari requirementValues berdasarkan kata kunci nama kriteria.
         */
        $getRv = function ($rvs, string $keyword) {
            if (! $rvs) {
                return '-';
            }
            $match = $rvs->first(fn ($rv) => str_contains(
                strtolower($rv->requirement?->requirement_name ?? ''),
                strtolower($keyword)
            ));
            return $match ? $match->applicant_value : '-';
        };

        /**
         * Normalisasi status DTKS:
         */
        $dtksLabel = function ($val) {
            return match ((string) $val) {
                '1'     => 'Desil 1',
                '2'     => 'Desil 2',
                '3'     => 'Desil 3',
                default => 'Tidak Terdaftar',
            };
        };

        /**
         * Normalisasi Prestasi Akademik:
         *   0 → Tidak Ada (0)
         *   1 → Sedikit (1)
         *   2 → Sedikit (2)
         *   3 → Sedang (3)
         *   4 → Banyak (4)
         *   5 → Banyak (5)
         */
        $prestasiLabel = function ($val) {
            if ($val === '-' || $val === '' || $val === null) {
                return '-';
            }
            $n = (int) $val;
            $label = match (true) {
                $n <= 0  => 'Tidak Ada',
                $n <= 2  => 'Sedikit',
                $n <= 3  => 'Sedang',
                $n <= 4  => 'Banyak',
                default  => 'Sangat Banyak',
            };
            return "{$label} ({$n})";
        };
    @endphp
    <tr>
        <td colspan="11" style="text-align: center; font-weight: bold; font-size: 14px;">YAYASAN PENDIDIKAN SURYAKANCANA</td>
    </tr>
    <tr>
        <td colspan="11" style="text-align: center; font-weight: bold; font-size: 16px;">UNIVERSITAS SURYAKANCANA</td>
    </tr>
    <tr>
        <td colspan="11" style="text-align: center; font-weight: bold; font-size: 20px;">FAKULTAS TEKNIK</td>
    </tr>
    <tr>
        <td colspan="11" style="text-align: center; font-size: 11px;">Program Studi: Teknik Sipil (S1) - Teknik Industri (S1) - Teknik Informatika (S1)</td>
    </tr>
    <tr>
        <td colspan="11" style="text-align: center; font-size: 11px;">Jalan Pasir Gede Raya, Cianjur 43216 Telp./Fax. (0263) 283578</td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11" style="text-align: center; font-weight: bold; font-size: 14px; text-decoration: underline;">LAPORAN DATA SELEKSI BEASISWA</td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="2">Total Data</td>
        <td>: {{ $data->count() }} Mahasiswa</td>
        <td colspan="2">Layak</td>
        <td colspan="6">: {{ $data->where('status', 'layak')->count() }} Mahasiswa</td>
    </tr>
    <tr>
        <td colspan="2">Sedang Diproses</td>
        <td>: {{ $data->whereNotIn('status', ['layak', 'tidak layak', 'diterima', 'tidak diterima'])->count() }} Mahasiswa</td>
        <td colspan="2">Tidak Layak</td>
        <td colspan="6">: {{ $data->where('status', 'tidak diterima')->count() }} Mahasiswa</td>
    </tr>
    @if (!empty(array_filter($filters)))
        <tr>
            <td colspan="11">
                Filter Aktif:
                @if ($filters['search'] ?? null)
                    [Pencarian: {{ $filters['search'] }}]
                @endif
                @if ($filters['scholarship_id'] ?? null)
                    [Beasiswa ID: {{ $filters['scholarship_id'] }}]
                @endif
                @if ($filters['stage'] ?? null)
                    [Tahap: {{ $filters['stage'] }}]
                @endif
                @if ($filters['status'] ?? null)
                    [Status: {{ ucwords($filters['status']) }}]
                @endif
            </td>
        </tr>
    @endif
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">No</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Nama Mahasiswa</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">NPM</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Beasiswa</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Penghasilan OT (Rp)</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Tanggungan OT</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Status DTKS</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Prestasi</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Wawancara</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Status</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Tanggal</th>
    </tr>
    @forelse($data as $i => $item)
        @php
            $rvs         = $item->application?->requirementValues ?? collect();
            $penghasilan = $getRv($rvs, 'penghasilan');
            $tanggungan  = $getRv($rvs, 'tanggungan');
            $dtksRaw     = $getRv($rvs, 'dtks');
            $prestasi    = $getRv($rvs, 'prestasi');

            // Format penghasilan sebagai Rupiah jika numerik
            $penghasilanFmt = is_numeric($penghasilan)
                ? 'Rp ' . number_format((float) $penghasilan, 0, ',', '.')
                : $penghasilan;

            // Nilai wawancara: rata-rata dari interview assessments
            $nilaiWawancara = '-';
            $interview = $item->application?->interviews?->first();
            if ($interview && $interview->assessments->isNotEmpty()) {
                $avg = round($interview->assessments->avg('score'), 2);
                $nilaiWawancara = $avg;
            }

            // Mapping status
            $displayStatus = match (strtolower($item->status)) {
                'tidak diterima' => 'Tidak Layak',
                'layak'          => 'Layak',
                default          => ucwords($item->status),
            };
        @endphp
        <tr>
            <td style="text-align: center; border: 1px solid #000000;">{{ $i + 1 }}</td>
            <td style="border: 1px solid #000000;">{{ $item->application?->student?->name ?? '-' }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $item->application?->student?->student_number ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $item->application?->scholarship?->scholarship_name ?? '-' }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $penghasilanFmt }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $tanggungan !== '-' ? $tanggungan . ' orang' : '-' }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $dtksLabel($dtksRaw) }}</td>
            <td style="border: 1px solid #000000;">{{ $prestasiLabel($prestasi) }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $nilaiWawancara }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ $displayStatus }}</td>
            <td style="text-align: center; border: 1px solid #000000;">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="11" style="text-align: center; border: 1px solid #000000;">Tidak ada data seleksi.</td>
        </tr>
    @endforelse
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="2" style="text-align: center;">Cianjur, {{ now()->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="2" style="text-align: center;">Mengetahui,</td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="2" style="text-align: center;">Ketua Tim Seleksi</td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="11"></td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="2" style="text-align: center;"><strong>_________________________</strong></td>
    </tr>
    <tr>
        <td colspan="9"></td>
        <td colspan="2" style="text-align: center;">NIDN. ................................</td>
    </tr>
</table>
