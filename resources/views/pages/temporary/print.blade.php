@php
    $fmtDate = fn ($date) => $date
        ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d/m/Y')
        : '-';

    $enumLabel = function ($value): string {
        if ($value instanceof \BackedEnum) {
            return method_exists($value, 'label') ? $value->label() : (string) $value->value;
        }

        return filled($value) ? (string) $value : '-';
    };

    $enumValue = fn ($value): string => $value instanceof \BackedEnum
        ? (string) $value->value
        : (string) ($value ?? '');
@endphp

<x-layouts.print-layout
    title="Buku Penduduk Sementara"
    paper="330mm 215mm"
    fontSize="7pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 2.5%;">NOMOR<br>URUT</th>
                <th rowspan="2" style="width: 11%;">NAMA<br>LENGKAP</th>
                <th colspan="2">JENIS<br>KELAMIN</th>
                <th rowspan="2" style="width: 8%;">NOMOR<br>IDENTITAS /<br>TANDA<br>PENGENAL</th>
                <th rowspan="2" style="width: 10%;">TEMPAT DAN<br>TANGGAL LAHIR /<br>UMUR</th>
                <th rowspan="2" style="width: 7%;">PEKERJAAN</th>
                <th colspan="2">KEWARGANEGARAAN</th>
                <th rowspan="2" style="width: 8.5%;">DATANG<br>DARI</th>
                <th rowspan="2" style="width: 9.5%;">MAKSUD DAN<br>TUJUAN<br>KEDATANGAN</th>
                <th rowspan="2" style="width: 10%;">NAMA DAN<br>ALAMAT YG<br>DIDATANGI</th>
                <th rowspan="2" style="width: 5.5%;">DATANG<br>TANGGAL</th>
                <th rowspan="2" style="width: 5.5%;">PERGI<br>TANGGAL</th>
                <th rowspan="2" style="width: 5.5%;">KET.</th>
            </tr>
            <tr>
                <th style="width: 2.5%;">L</th>
                <th style="width: 2.5%;">P</th>
                <th style="width: 6%;">KEBANGSAAN</th>
                <th style="width: 6%;">KETURUNAN</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 15; $i++)
                    <th style="font-size: 6.5pt; padding: 2px;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                @php
                    $jk = $enumValue($row->jenis_kelamin);
                    $umur = (int) $row->umur > 0
                        ? (int) $row->umur
                        : ($row->tgl_lahir ? \Carbon\Carbon::parse($row->tgl_lahir)->age : null);
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $row->nama_lengkap ?: '-' }}</td>
                    <td class="text-center fw-bold">{{ $jk === 'L' ? 'L' : '' }}</td>
                    <td class="text-center fw-bold">{{ $jk === 'P' ? 'P' : '' }}</td>
                    <td class="text-center text-nowrap">{{ $row->nomor_identitas ?: '-' }}</td>
                    <td>
                        {{ $row->tempat_lahir ?: '-' }}@if ($row->tgl_lahir), {{ $fmtDate($row->tgl_lahir) }}@endif
                        @if (!is_null($umur))
                            <br>({{ $umur }} thn)
                        @endif
                    </td>
                    <td>{{ $enumLabel($row->pekerjaan) }}</td>
                    <td class="text-center">{{ $row->kebangsaan ?: $enumLabel($row->kewarganegaraan) }}</td>
                    <td class="text-center">{{ $row->keturunan ?: '-' }}</td>
                    <td>{{ $row->datang_dari ?: '-' }}</td>
                    <td>{{ $row->tujuan_kedatangan ?: '-' }}</td>
                    <td>{{ $row->alamat_tujuan ?: '-' }}</td>
                    <td class="text-center text-nowrap">{{ $fmtDate($row->tgl_datang) }}</td>
                    <td class="text-center text-nowrap">{{ $fmtDate($row->tgl_pergi) }}</td>
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" class="text-center" style="padding: 12px;">
                        Tidak ada data penduduk sementara untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>