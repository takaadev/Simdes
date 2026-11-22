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

    $enumValue = fn ($value): ?string => $value instanceof \BackedEnum
        ? (string) $value->value
        : (filled($value) ? (string) $value : null);
@endphp

<x-layouts.print-layout
    title="Buku Induk Penduduk"
    paper="330mm 215mm"
    fontSize="7pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 2.5%;">NO.</th>
                <th rowspan="2" style="width: 11%;">NAMA LENGKAP /<br>PANGGILAN</th>
                <th rowspan="2" style="width: 3.5%;">JENIS<br>KELAMIN</th>
                <th rowspan="2" style="width: 6%;">STATUS<br>PERKAWINAN</th>
                <th colspan="2">TEMPAT &amp; TANGGAL LAHIR</th>
                <th rowspan="2" style="width: 5.5%;">AGAMA</th>
                <th rowspan="2" style="width: 7%;">PENDIDIKAN<br>TERAKHIR</th>
                <th rowspan="2" style="width: 8%;">PEKERJAAN</th>
                <th rowspan="2" style="width: 4.5%;">DAPAT<br>MEMBACA<br>HURUF</th>
                <th rowspan="2" style="width: 4.5%;">KEWARGA-<br>NEGARAAN</th>
                <th rowspan="2" style="width: 11%;">ALAMAT LENGKAP</th>
                <th rowspan="2" style="width: 6.5%;">KEDUDUKAN<br>DLM KELUARGA</th>
                <th rowspan="2" style="width: 7.5%;">NIK</th>
                <th rowspan="2" style="width: 7.5%;">NOMOR KK</th>
                <th rowspan="2" style="width: 4.5%;">KET.</th>
            </tr>
            <tr>
                <th style="width: 5.5%;">TEMPAT LAHIR</th>
                <th style="width: 5%;">TGL</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 16; $i++)
                    <th style="font-size: 6.5pt; padding: 2px;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                @php
                    $rt = $enumValue($row->rt);
                    $rw = $enumValue($row->rw);
                    $alamatLengkap = collect([
                        $row->alamat,
                        $rt ? "RT {$rt}" : null,
                        $rw ? "RW {$rw}" : null,
                    ])->filter()->implode(', ');
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $row->nama_lengkap ?: '-' }}</td>
                    <td class="text-center">{{ $enumValue($row->jenis_kelamin) ?: '-' }}</td>
                    <td class="text-center">{{ $enumLabel($row->status_perkawinan) }}</td>
                    <td>{{ $row->tempat_lahir ?: '-' }}</td>
                    <td class="text-center text-nowrap">{{ $fmtDate($row->tgl_lahir) }}</td>
                    <td class="text-center">{{ $enumLabel($row->agama) }}</td>
                    <td>{{ $enumLabel($row->pendidikan) }}</td>
                    <td>{{ $enumLabel($row->pekerjaan) }}</td>
                    <td class="text-center">{{ $row->dapat_membaca ? 'Ya' : 'Tidak' }}</td>
                    <td class="text-center">{{ $enumLabel($row->kewarganegaraan) }}</td>
                    <td>{{ $alamatLengkap ?: '-' }}</td>
                    <td>{{ $enumLabel($row->status_dalam_keluarga) }}</td>
                    <td class="text-center text-nowrap">{{ $row->nik ?: '-' }}</td>
                    <td class="text-center text-nowrap">{{ $row->nomor_kk ?: ($row->kk ?: '-') }}</td>
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="16" class="text-center" style="padding: 12px;">
                        Tidak ada data penduduk untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>