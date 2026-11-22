@php
    $cols = [
        'awb_wni_l', 'awb_wni_p', 'awb_wna_l', 'awb_wna_p', 'awb_jml_kk', 'awb_jml_anggota_kk', 'awb_jml_penduduk',
        'tbl_wna_l', 'tbl_wna_p', 'tbl_wni_l', 'tbl_wni_p',
        'tbd_wna_l', 'tbd_wna_p', 'tbd_wni_l', 'tbd_wni_p',
        'pbm_wna_l', 'pbm_wna_p', 'pbm_wni_l', 'pbm_wni_p',
        'pbp_wna_l', 'pbp_wna_p', 'pbp_wni_l', 'pbp_wni_p',
        'ahb_wna_l', 'ahb_wna_p', 'ahb_wni_l', 'ahb_wni_p', 'ahb_jml_kk', 'ahb_jml_anggota_kk', 'ahb_jml_penduduk',
    ];
@endphp

<x-layouts.print-layout
    :title="'Rekapitulasi Jumlah Penduduk Bulan ' . strtoupper($periodeLabel)"
    paper="330mm 215mm"
    fontSize="6pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    @push('print-styles')
        <style>
            table.print-table th,
            table.print-table td {
                padding: 2.5px 2px;
            }
        </style>
    @endpush

    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="4" style="width: 2%;">NO</th>
                <th rowspan="4" style="width: 8%;">NAMA DUSUN /<br>LINGKUNGAN</th>
                <th colspan="7">JUMLAH PENDUDUK AWAL BULAN</th>
                <th colspan="8">TAMBAHAN BULAN INI</th>
                <th colspan="8">PENGURANGAN BULAN INI</th>
                <th colspan="7">JML PENDUDUK AKHIR BULAN</th>
                <th rowspan="4" style="width: 6%;">KET.</th>
            </tr>
            <tr>
                {{-- Awal Bulan --}}
                <th colspan="2" rowspan="2">WNI</th>
                <th colspan="2" rowspan="2">WNA</th>
                <th rowspan="3">JML<br>KK</th>
                <th rowspan="3">JML<br>ANGGOTA<br>KELUARGA</th>
                <th rowspan="3">JML<br>JIWA<br>(7+8)</th>

                {{-- Tambahan --}}
                <th colspan="4">LAHIR</th>
                <th colspan="4">DATANG</th>

                {{-- Pengurangan --}}
                <th colspan="4">MENINGGAL</th>
                <th colspan="4">PINDAH</th>

                {{-- Akhir Bulan --}}
                <th colspan="2" rowspan="2">WNA</th>
                <th colspan="2" rowspan="2">WNI</th>
                <th rowspan="3">JML<br>KK</th>
                <th rowspan="3">JML<br>ANGGOTA<br>KELUARGA</th>
                <th rowspan="3">JML<br>JIWA<br>(30+31)</th>
            </tr>
            <tr>
                <th colspan="2">WNA</th>
                <th colspan="2">WNI</th>
                <th colspan="2">WNA</th>
                <th colspan="2">WNI</th>
                <th colspan="2">WNA</th>
                <th colspan="2">WNI</th>
                <th colspan="2">WNA</th>
                <th colspan="2">WNI</th>
            </tr>
            <tr>
                {{-- 12 pasang L / P --}}
                @for ($p = 0; $p < 12; $p++)
                    <th>L</th>
                    <th>P</th>
                @endfor
            </tr>
            <tr>
                @for ($i = 1; $i <= 33; $i++)
                    <th style="font-size: 5.5pt; padding: 1.5px;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td class="text-nowrap">{{ $row->lingkungan ?: '-' }}</td>
                    @foreach ($cols as $col)
                        <td class="text-center {{ in_array($col, ['awb_jml_penduduk', 'ahb_jml_penduduk'], true) ? 'fw-bold' : '' }}">
                            {{ (int) $row->{$col} }}
                        </td>
                    @endforeach
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="33" class="text-center" style="padding: 12px;">
                        Belum ada data rekapitulasi penduduk untuk periode {{ $periodeLabel }}.
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if ($records->isNotEmpty())
            <tfoot>
                <tr class="fw-bold" style="background: #f9fafb;">
                    <td colspan="2" class="text-center">JUMLAH</td>
                    @foreach ($cols as $col)
                        <td class="text-center">{{ $records->sum($col) }}</td>
                    @endforeach
                    <td class="text-center">-</td>
                </tr>
            </tfoot>
        @endif
    </table>
</x-layouts.print-layout>