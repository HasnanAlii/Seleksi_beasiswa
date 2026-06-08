<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Seleksi Beasiswa</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            line-height: 1.5;
        }

        /* KOP SURAT */
        .kop-surat {
            width: 100%;
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
            text-align: center;
        }

        .kop-surat::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -5px;
            border-bottom: 1px solid #000;
        }

        .kop-surat .logo {
            position: absolute;
            left: 10px;
            top: 0;
            width: 90px;
        }

        .kop-surat .teks-yayasan {
            font-size: 14px;
            font-weight: bold;
        }

        .kop-surat .teks-univ {
            font-size: 16px;
            font-weight: bold;
        }

        .kop-surat .teks-fakultas {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 1px;
        }

        .kop-surat .teks-prodi {
            font-size: 11px;
            margin-top: 5px;
        }

        .kop-surat .teks-alamat {
            font-size: 11px;
        }

        /* JUDUL LAPORAN */
        .judul-laporan {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 20px;
            text-transform: uppercase;
            text-decoration: underline;
        }

        /* INFO / SUMMARY */
        .info-table {
            width: 100%;
            margin-bottom: 15px;
            font-size: 12px;
        }

        .info-table td {
            padding: 2px 5px;
            vertical-align: top;
        }

        /* TABEL DATA */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 6px 8px;
        }

        .data-table th {
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
            background-color: #f8f8f8;
        }

        .data-table td.center {
            text-align: center;
        }

        /* FOOTER / TTD */
        .ttd-container {
            width: 100%;
            margin-top: 40px;
        }

        .ttd-box {
            float: right;
            width: 250px;
            text-align: left;
        }
    </style>
</head>

<body>
    <div class="kop-surat">
        @php
            $path = public_path('images/icon/logoft.png');
            if (file_exists($path)) {
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $dataImg = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($dataImg);
            } else {
                $base64 = '';
            }
        @endphp
        @if ($base64)
            <img src="{{ $base64 }}" class="logo" alt="Logo FT UNSUR">
        @endif
        <div class="teks-yayasan">YAYASAN PENDIDIKAN SURYAKANCANA</div>
        <div class="teks-univ">UNIVERSITAS SURYAKANCANA</div>
        <div class="teks-fakultas">FAKULTAS TEKNIK</div>
        <div class="teks-prodi">Program Studi: Teknik Sipil (S1) - Teknik Industri (S1) - Teknik Informatika (S1)</div>
        <div class="teks-alamat">Jalan Pasir Gede Raya, Cianjur 43216 Telp./Fax. (0263) 283578</div>
    </div>

    <div class="judul-laporan">
        Laporan Data Seleksi Beasiswa
    </div>

    <table class="info-table">
        <tr>
            <td width="120">Total Data</td>
            <td width="10">:</td>
            <td>{{ $data->count() }} Mahasiswa</td>

            <td width="120">Layak</td>
            <td width="10">:</td>
            <td>{{ $data->where('status', 'layak')->count() }} Mahasiswa</td>
        </tr>
        <tr>
            <td>Sedang Diproses</td>
            <td>:</td>
            <td>{{ $data->whereNotIn('status', ['layak', 'tidak layak', 'diterima', 'tidak diterima'])->count() }}
                Mahasiswa</td>

            <td>Tidak Layak</td>
            <td>:</td>
            <td>{{ $data->where('status', 'tidak layak')->count() }} Mahasiswa</td>
        </tr>
        @if (!empty(array_filter($filters)))
            <tr>
                <td colspan="6" style="padding-top: 10px;">
                    <strong>Filter Aktif:</strong>
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
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 30px;">No</th>
                <th>Nama Mahasiswa</th>
                <th>NPM</th>
                <th>Beasiswa</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $item)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $item->application?->student?->name ?? '-' }}</td>
                    <td class="center">{{ $item->application?->student?->student_number ?? '-' }}</td>
                    <td>{{ $item->application?->scholarship?->scholarship_name ?? '-' }}</td>
                    <td class="center">{{ ucwords($item->status) }}</td>
                    <td class="center">{{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="center">Tidak ada data seleksi.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="ttd-container">
        <div class="ttd-box">
            <p>Cianjur, {{ now()->translatedFormat('d F Y') }}</p>
            <p style="margin-bottom: 60px;">Mengetahui,<br>Ketua Tim Seleksi</p>
            <p><strong>_________________________</strong><br>NIDN. ................................</p>
        </div>
        <div style="clear: both;"></div>
    </div>
</body>

</html>
