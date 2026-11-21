@php
    $fmtArea = fn ($val) => (float) $val > 0 ? number_format((float) $val, 2, ',', '.') : '-';
    $fmtDate = fn ($date) => $date
        ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d/m/Y')
        : '-';

    $enumLabel = function ($value): string {
        if ($value instanceof \BackedEnum) {
            return method_exists($value, 'label') ? $value->label() : (string) $value->value;
        }

        return filled($value) ? (string) $value : '-';
    };
@endphp

<x-layouts.print-layout
    title="Buku Tanah Kas Desa"
    paper="330mm 215mm"
    fontSize="6.8pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="3" style="width: 2.5%;">NO</th>
                <th rowspan="3" style="width: 7%;">ASAL TANAH<br>KAS DESA</th>
                <th rowspan="3" style="width: 8%;">NOMOR SERTIFIKAT<br>BUKU LETTER C /<br>PERSIL</th>
                <th rowspan="3" style="width: 4.5%;">LUAS<br>(m²)</th>
                <th rowspan="3" style="width: 3.5%;">KELAS</th>
                <th colspan="6">PEROLEHAN TANAH KAS DESA</th>
                <th colspan="5">JENIS TANAH KAS DESA</th>
                <th colspan="2">PATOK<br>TANDA<br>BATAS</th>
                <th colspan="2">PAPAN<br>NAMA</th>
                <th rowspan="3" style="width: 7.5%;">LOKASI</th>
                <th rowspan="3" style="width: 7%;">PERUNTUKAN</th>
                <th rowspan="3" style="width: 5.5%;">MUTASI</th>
                <th rowspan="3" style="width: 5.5%;">KET.</th>
            </tr>
            <tr>
                <th rowspan="2">ASLI<br>MILIK<br>DESA</th>
                <th colspan="3">BANTUAN</th>
                <th rowspan="2">LAIN-<br>LAIN</th>
                <th rowspan="2">TGL<br>PEROLEHAN</th>
                <th rowspan="2">SAWAH</th>
                <th rowspan="2">TEGAL</th>
                <th rowspan="2">KEBUN</th>
                <th rowspan="2">TAMBAK /<br>KOLAM</th>
                <th rowspan="2">TANAH<br>KERING /<br>DARAT</th>
                <th rowspan="2">ADA</th>
                <th rowspan="2">TDK<br>ADA</th>
                <th rowspan="2">ADA</th>
                <th rowspan="2">TDK<br>ADA</th>
            </tr>
            <tr>
                <th>PEME-<br>RINTAH</th>
                <th>PROV</th>
                <th>KAB/<br>KOTA</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 24; $i++)
                    <th style="font-size: 6.5pt; padding: 2px;"><em>{{ $i }}</em></th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $enumLabel($row->asal_tanah) }}</td>
                    <td>{{ $row->nomor_sertifikat ?: '-' }}</td>
                    <td class="text-end fw-bold">{{ $fmtArea($row->luas_tanah) }}</td>
                    <td class="text-center">{{ $enumLabel($row->kelas_tanah) }}</td>

                    {{-- Perolehan Tanah Kas Desa --}}
                    <td class="text-end">{{ $fmtArea($row->lt_asli_desa) }}</td>
                    <td class="text-end">{{ $fmtArea($row->lt_pemerintah) }}</td>
                    <td class="text-end">{{ $fmtArea($row->lt_provinsi) }}</td>
                    <td class="text-end">{{ $fmtArea($row->lt_kabupaten) }}</td>
                    <td class="text-end">{{ $fmtArea($row->lt_lainnya) }}</td>
                    <td class="text-center text-nowrap">{{ $fmtDate($row->tgl_perolehan) }}</td>

                    {{-- Jenis Tanah Kas Desa --}}
                    <td class="text-end">{{ $fmtArea($row->lt_tipe_sawah) }}</td>
                    <td class="text-end">{{ $fmtArea($row->lt_tipe_tegal) }}</td>
                    <td class="text-end">{{ $fmtArea($row->lt_tipe_kebun) }}</td>
                    <td class="text-end">{{ $fmtArea($row->lt_tipe_tambak) }}</td>
                    <td class="text-end">{{ $fmtArea($row->lt_tipe_kering) }}</td>

                    {{-- Patok Tanda Batas (✓ / ✕) --}}
                    <td class="text-center fw-bold">{{ $row->patok_tanah ? '✓' : '' }}</td>
                    <td class="text-center fw-bold">{{ !$row->patok_tanah ? '✕' : '' }}</td>

                    {{-- Papan Nama (✓ / ✕) --}}
                    <td class="text-center fw-bold">{{ $row->papan_nama ? '✓' : '' }}</td>
                    <td class="text-center fw-bold">{{ !$row->papan_nama ? '✕' : '' }}</td>

                    {{-- Lokasi, Peruntukan, Mutasi, Keterangan --}}
                    <td>{{ $row->lokasi ?: '-' }}</td>
                    <td>{{ $row->peruntukan ?: '-' }}</td>
                    <td>{{ $row->mutasi ?: '-' }}</td>
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="24" class="text-center" style="padding: 12px;">
                        Tidak ada data tanah kas desa untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if ($records->isNotEmpty())
            <tfoot>
                <tr class="fw-bold" style="background: #f9fafb;">
                    <td colspan="3" class="text-center">JUMLAH</td>
                    <td class="text-end">{{ number_format($records->sum('luas_tanah'), 2, ',', '.') }}</td>
                    <td class="text-center">-</td>
                    <td class="text-end">{{ number_format($records->sum('lt_asli_desa'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('lt_pemerintah'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('lt_provinsi'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('lt_kabupaten'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('lt_lainnya'), 2, ',', '.') }}</td>
                    <td class="text-center">-</td>
                    <td class="text-end">{{ number_format($records->sum('lt_tipe_sawah'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('lt_tipe_tegal'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('lt_tipe_kebun'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('lt_tipe_tambak'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('lt_tipe_kering'), 2, ',', '.') }}</td>
                    <td class="text-center">{{ $records->where('patok_tanah', true)->count() }}</td>
                    <td class="text-center">{{ $records->where('patok_tanah', false)->count() }}</td>
                    <td class="text-center">{{ $records->where('papan_nama', true)->count() }}</td>
                    <td class="text-center">{{ $records->where('papan_nama', false)->count() }}</td>
                    <td colspan="4" class="text-center">-</td>
                </tr>
            </tfoot>
        @endif
    </table>
</x-layouts.print-layout>