@php
    $fmtArea = fn ($val) => (float) $val > 0 ? number_format((float) $val, 2, ',', '.') : '-';
@endphp

<x-layouts.print-layout
    title="Buku Tanah di Desa"
    paper="330mm 215mm"
    fontSize="6.5pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="3" style="width: 2.5%;">NOMOR<br>URUT</th>
                <th rowspan="3" style="width: 11%;">NAMA PER-ORANGAN /<br>BADAN HUKUM</th>
                <th rowspan="3" style="width: 4.5%;">JML<br>(M²)</th>
                <th colspan="8">STATUS HAK TANAH (M²)</th>
                <th colspan="13">PENGGUNAAN TANAH (M²)</th>
                <th rowspan="3" style="width: 4.5%;">LAIN-LAIN</th>
                <th rowspan="3" style="width: 6.5%;">KET.</th>
            </tr>
            <tr>
                <th colspan="5">SUDAH BERSERTIFIKAT</th>
                <th colspan="3">BELUM BERSERTIFIKAT</th>
                <th colspan="5">NON PERTANIAN</th>
                <th colspan="8">PERTANIAN</th>
            </tr>
            <tr>
                <th>HM</th>
                <th>HGB</th>
                <th>HP</th>
                <th>HGU</th>
                <th>HPL</th>
                <th>MA</th>
                <th>VI</th>
                <th>TN</th>
                <th>PERU-<br>MAHAN</th>
                <th>PERDAGANG-<br>AN &amp; JASA</th>
                <th>PERKAN-<br>TORAN</th>
                <th>INDUS-<br>TRI</th>
                <th>FASILITAS<br>UMUM</th>
                <th>SAWAH</th>
                <th>TEGA-<br>LAN</th>
                <th>PERKE-<br>BUNAN</th>
                <th>PETERNAKAN/<br>PERIKANAN</th>
                <th>HUTAN<br>BELUKAR</th>
                <th>HUTAN LEBAT/<br>LINDUNG</th>
                <th>MUTASI<br>TANAH<br>DI DESA</th>
                <th>TANAH<br>KOSONG</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 26; $i++)
                    <th style="font-size: 6pt; padding: 2px;"><em>{{ $i }}</em></th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $row->owner_name ?: '-' }}</td>
                    <td class="text-end fw-bold">{{ $fmtArea($row->jml) }}</td>

                    {{-- Status Hak Tanah --}}
                    <td class="text-end">{{ $fmtArea($row->hm) }}</td>
                    <td class="text-end">{{ $fmtArea($row->hgb) }}</td>
                    <td class="text-end">{{ $fmtArea($row->hp) }}</td>
                    <td class="text-end">{{ $fmtArea($row->hgu) }}</td>
                    <td class="text-end">{{ $fmtArea($row->hpl) }}</td>
                    <td class="text-end">{{ $fmtArea($row->ma) }}</td>
                    <td class="text-end">{{ $fmtArea($row->vi) }}</td>
                    <td class="text-end">{{ $fmtArea($row->tn) }}</td>

                    {{-- Penggunaan Tanah — Non Pertanian --}}
                    <td class="text-end">{{ $fmtArea($row->perumahan) }}</td>
                    <td class="text-end">{{ $fmtArea($row->dagang_jasa) }}</td>
                    <td class="text-end">{{ $fmtArea($row->perkantoran) }}</td>
                    <td class="text-end">{{ $fmtArea($row->industri) }}</td>
                    <td class="text-end">{{ $fmtArea($row->fasilitas_umum) }}</td>

                    {{-- Penggunaan Tanah — Pertanian, Mutasi & Tanah Kosong --}}
                    <td class="text-end">{{ $fmtArea($row->sawah) }}</td>
                    <td class="text-end">{{ $fmtArea($row->tegalan) }}</td>
                    <td class="text-end">{{ $fmtArea($row->perkebunan) }}</td>
                    <td class="text-end">{{ $fmtArea($row->peternakan_perikanan) }}</td>
                    <td class="text-end">{{ $fmtArea($row->hutan_belukar) }}</td>
                    <td class="text-end">{{ $fmtArea($row->hutan_lebat) }}</td>
                    <td class="text-end">{{ $fmtArea($row->mutasi_tanah) }}</td>
                    <td class="text-end">{{ $fmtArea($row->tanah_kosong) }}</td>

                    {{-- Lain-lain & Keterangan --}}
                    <td class="text-end">{{ $fmtArea($row->lainnya) }}</td>
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="26" class="text-center" style="padding: 12px;">
                        Tidak ada data tanah di desa untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>

        @if ($records->isNotEmpty())
            <tfoot>
                <tr class="fw-bold" style="background: #f9fafb;">
                    <td colspan="2" class="text-center">JUMLAH</td>
                    <td class="text-end">{{ number_format($records->sum('jml'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('hm'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('hgb'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('hp'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('hgu'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('hpl'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('ma'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('vi'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('tn'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('perumahan'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('dagang_jasa'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('perkantoran'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('industri'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('fasilitas_umum'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('sawah'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('tegalan'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('perkebunan'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('peternakan_perikanan'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('hutan_belukar'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('hutan_lebat'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('mutasi_tanah'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('tanah_kosong'), 2, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($records->sum('lainnya'), 2, ',', '.') }}</td>
                    <td class="text-center">-</td>
                </tr>
            </tfoot>
        @endif
    </table>
</x-layouts.print-layout>