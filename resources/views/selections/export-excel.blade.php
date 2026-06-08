<table>
    <tr>
        <td colspan="6" style="text-align: center; font-weight: bold; font-size: 14px;">YAYASAN PENDIDIKAN SURYAKANCANA
        </td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; font-weight: bold; font-size: 16px;">UNIVERSITAS SURYAKANCANA</td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; font-weight: bold; font-size: 20px;">FAKULTAS TEKNIK</td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; font-size: 11px;">Program Studi: Teknik Sipil (S1) - Teknik Industri
            (S1) - Teknik Informatika (S1)</td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; font-size: 11px;">Jalan Pasir Gede Raya, Cianjur 43216 Telp./Fax.
            (0263) 283578</td>
    </tr>
    <tr>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="6" style="text-align: center; font-weight: bold; font-size: 14px; text-decoration: underline;">
            LAPORAN DATA SELEKSI BEASISWA</td>
    </tr>
    <tr>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="2">Total Data</td>
        <td>: {{ $data->count() }} Mahasiswa</td>
        <td colspan="2">Layak</td>
        <td>: {{ $data->where('status', 'layak')->count() }} Mahasiswa</td>
    </tr>
    <tr>
        <td colspan="2">Sedang Diproses</td>
        <td>: {{ $data->whereNotIn('status', ['layak', 'tidak layak', 'diterima', 'tidak diterima'])->count() }}
            Mahasiswa</td>
        <td colspan="2">Tidak Layak</td>
        <td>: {{ $data->where('status', 'tidak layak')->count() }} Mahasiswa</td>
    </tr>
    @if (!empty(array_filter($filters)))
        <tr>
            <td colspan="6">
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
        <td colspan="6"></td>
    </tr>
    <tr>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">No</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Nama
            Mahasiswa</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">NPM
        </th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">
            Beasiswa</th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Status
        </th>
        <th style="font-weight: bold; text-align: center; border: 1px solid #000000; background-color: #f8f8f8;">Tanggal
        </th>
    </tr>
    @forelse($data as $i => $item)
        <tr>
            <td style="text-align: center; border: 1px solid #000000;">{{ $i + 1 }}</td>
            <td style="border: 1px solid #000000;">{{ $item->application?->student?->name ?? '-' }}</td>
            <td style="text-align: center; border: 1px solid #000000;">
                {{ $item->application?->student?->student_number ?? '-' }}</td>
            <td style="border: 1px solid #000000;">{{ $item->application?->scholarship?->scholarship_name ?? '-' }}
            </td>
            <td style="text-align: center; border: 1px solid #000000;">{{ ucwords($item->status) }}</td>
            <td style="text-align: center; border: 1px solid #000000;">
                {{ \Carbon\Carbon::parse($item->date)->translatedFormat('d M Y') }}</td>
        </tr>
    @empty
        <tr>
            <td colspan="6" style="text-align: center; border: 1px solid #000000;">Tidak ada data seleksi.</td>
        </tr>
    @endforelse
    <tr>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2" style="text-align: center;">Cianjur, {{ now()->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2" style="text-align: center;">Mengetahui,</td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2" style="text-align: center;">Ketua Tim Seleksi</td>
    </tr>
    <tr>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="6"></td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2" style="text-align: center;"><strong>_________________________</strong></td>
    </tr>
    <tr>
        <td colspan="4"></td>
        <td colspan="2" style="text-align: center;">NIDN. ................................</td>
    </tr>
</table>
