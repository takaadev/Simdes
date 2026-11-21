@php
    $fmtNum = fn ($val) => (int) $val > 0 ? number_format((int) $val, 0, ',', '.') : '-';
    $fmtDate = fn ($date) => $date
        ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d/m/Y')
        : '-';
@endphp

<x-layouts.print-layout
    title="Buku Inventaris dan Kekayaan Desa"
    paper="A4 landscape"
    fontSize="7.5pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="3" style="width: 3%;">NO.<br>URUT</th>
                <th rowspan="3" style="width: 17%;">JENIS BARANG /<br>BANGUNAN</th>
                <th colspan="5">ASAL BARANG / BANGUNAN</th>
                <th colspan="2">KEADAAN BARANG /<br>BANGUNAN AWAL TAHUN</th>
                <th colspan="4">PENGHAPUSAN BARANG DAN BANGUNAN</th>
                <th colspan="2">KEADAAN BARANG /<br>BANGUNAN AKHIR TAHUN</th>
                <th rowspan="3" style="width: 11%;">KET.</th>
            </tr>
            <tr>
                <th rowspan="2" style="width: 5.5%;">DIBELI<br>SENDIRI</th>
                <th colspan="3">BANTUAN</th>
                <th rowspan="2" style="width: 6%;">SUMBANGAN</th>
                <th rowspan="2" style="width: 4.5%;">BAIK</th>
                <th rowspan="2" style="width: 4.5%;">RUSAK</th>
                <th rowspan="2" style="width: 4.5%;">RUSAK</th>
                <th rowspan="2" style="width: 4.5%;">DIJUAL</th>
                <th rowspan="2" style="width: 6%;">DISUMBANGKAN</th>
                <th rowspan="2" style="width: 7%;">TGL<br>PENGHAPUSAN</th>
                <th rowspan="2" style="width: 4.5%;">BAIK</th>
                <th rowspan="2" style="width: 4.5%;">RUSAK</th>
            </tr>
            <tr>
                <th style="width: 5.5%;">PEMERINTAH</th>
                <th style="width: 5.5%;">PROVINSI</th>
                <th style="width: 5.5%;">KAB/KOTA</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 16; $i++)
                    <th style="font-size: 7pt; padding: 2px;"><em>{{ $i }}</em></th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $row->nama_asset ?: '-' }}</td>
                    <td class="text-center">{{ $fmtNum($row->asal_beli) }}</td>
                    <td class="text-center">{{ $fmtNum($row->asal_pemerintah) }}</td>
                    <td class="text-center">{{ $fmtNum($row->asal_provinsi) }}</td>
                    <td class="text-center">{{ $fmtNum($row->asal_kabupaten) }}</td>
                    <td class="text-center">{{ $fmtNum($row->asal_sumbangan) }}</td>
                    <td class="text-center">{{ $fmtNum($row->kondisi_awal_baik) }}</td>
                    <td class="text-center">{{ $fmtNum($row->kondisi_awal_rusak) }}</td>
                    <td class="text-center">{{ $fmtNum($row->hapus_rusak) }}</td>
                    <td class="text-center">{{ $fmtNum($row->hapus_dijual) }}</td>
                    <td class="text-center">{{ $fmtNum($row->hapus_disumbangkan) }}</td>
                    <td class="text-center text-nowrap">{{ $fmtDate($row->tgl_hapus) }}</td>
                    <td class="text-center">{{ $fmtNum($row->kondisi_ahir_baik) }}</td>
                    <td class="text-center">{{ $fmtNum($row->kondisi_ahir_rusak) }}</td>
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="text-center" style="padding: 12px;">
                        Tidak ada data inventaris dan kekayaan desa untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if ($records->isNotEmpty())
            <tfoot>
                <tr class="fw-bold" style="background: #f9fafb;">
                    <td colspan="2" class="text-center">JUMLAH</td>
                    <td class="text-center">{{ number_format($records->sum('asal_beli'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('asal_pemerintah'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('asal_provinsi'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('asal_kabupaten'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('asal_sumbangan'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('kondisi_awal_baik'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('kondisi_awal_rusak'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('hapus_rusak'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('hapus_dijual'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('hapus_disumbangkan'), 0, ',', '.') }}</td>
                    <td class="text-center">-</td>
                    <td class="text-center">{{ number_format($records->sum('kondisi_ahir_baik'), 0, ',', '.') }}</td>
                    <td class="text-center">{{ number_format($records->sum('kondisi_ahir_rusak'), 0, ',', '.') }}</td>
                    <td class="text-center">-</td>
                </tr>
            </tfoot>
        @endif
    </table>
</x-layouts.print-layout>