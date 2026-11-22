@php
    $fmtDate = fn ($date) => $date
        ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d/m/Y')
        : null;

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
    title="Buku Kartu Tanda Penduduk dan Buku Kartu Keluarga"
    paper="330mm 215mm"
    fontSize="6.5pt"
    :totalRecords="$totalResidents"
    :filterSummary="$filterSummary"
>
    @push('print-styles')
        <style>
            table.print-table td.kk-cell {
                vertical-align: middle !important;
                text-align: center !important;
                font-weight: 700;
            }
        </style>
    @endpush

    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 2.5%;">NOMOR<br>URUT</th>
                <th rowspan="2" style="width: 7.5%;">NO. KK</th>
                <th rowspan="2" style="width: 9%;">NAMA<br>LENGKAP</th>
                <th rowspan="2" style="width: 7.5%;">NIK</th>
                <th rowspan="2" style="width: 2.5%;">JK</th>
                <th rowspan="2" style="width: 7.5%;">TEMPAT /<br>TANGGAL<br>LAHIR</th>
                <th rowspan="2" style="width: 2.5%;">GOL.<br>DARAH</th>
                <th rowspan="2" style="width: 4.5%;">AGAMA</th>
                <th rowspan="2" style="width: 5%;">PENDI-<br>DIKAN</th>
                <th rowspan="2" style="width: 6%;">PEKER-<br>JAAN</th>
                <th rowspan="2" style="width: 8%;">ALAMAT</th>
                <th rowspan="2" style="width: 5%;">STATUS<br>PERKAWINAN</th>
                <th rowspan="2" style="width: 7%;">TEMPAT DAN<br>TANGGAL<br>DIKELUARKAN</th>
                <th rowspan="2" style="width: 5.5%;">STATUS HUB.<br>KELUARGA</th>
                <th rowspan="2" style="width: 4%;">KEWARGA-<br>NEGARAAN</th>
                <th colspan="2">ORANG TUA</th>
                <th rowspan="2" style="width: 5%;">TGL MULAI<br>TINGGAL<br>DI DESA</th>
                <th rowspan="2" style="width: 4%;">KET.</th>
            </tr>
            <tr>
                <th style="width: 5.5%;">AYAH</th>
                <th style="width: 5.5%;">IBU</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 19; $i++)
                    <th style="font-size: 6pt; padding: 1.5px;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($groups as $members)
                @php
                    $groupNo = $loop->iteration;
                    $memberCount = $members->count();
                    $nomorKk = $members->first()->nomor_kk ?: '-';
                @endphp

                @foreach ($members as $row)
                    @php
                        $rt = $enumValue($row->rt);
                        $rw = $enumValue($row->rw);
                        $alamatLengkap = collect([
                            $row->alamat,
                            $rt ? "RT {$rt}" : null,
                            $rw ? "RW {$rw}" : null,
                        ])->filter()->implode(', ');

                        $tglKeluar = $fmtDate($row->tgl_dikeluarkan);
                    @endphp
                    <tr>
                        @if ($loop->first)
                            <td rowspan="{{ $memberCount }}" class="kk-cell">{{ $groupNo }}.</td>
                            <td rowspan="{{ $memberCount }}" class="kk-cell text-nowrap">{{ $nomorKk }}</td>
                        @endif

                        <td>{{ $row->nama_lengkap ?: '-' }}</td>
                        <td class="text-center text-nowrap">{{ $row->nik ?: '-' }}</td>
                        <td class="text-center">{{ $enumValue($row->jenis_kelamin) ?: '-' }}</td>
                        <td>
                            {{ $row->tempat_lahir ?: '-' }}@if ($row->tgl_lahir),<br>{{ $fmtDate($row->tgl_lahir) }}@endif
                        </td>
                        <td class="text-center">{{ $enumLabel($row->gol_darah) }}</td>
                        <td class="text-center">{{ $enumLabel($row->agama) }}</td>
                        <td>{{ $enumLabel($row->pendidikan) }}</td>
                        <td>{{ $enumLabel($row->pekerjaan) }}</td>
                        <td>{{ $alamatLengkap ?: '-' }}</td>
                        <td class="text-center">{{ $enumLabel($row->status_perkawinan) }}</td>
                        <td>
                            @if ($row->tempat_dikeluarkan || $tglKeluar)
                                {{ $row->tempat_dikeluarkan ?: '-' }}@if ($tglKeluar),<br>{{ $tglKeluar }}@endif
                            @else
                                <div class="text-center">-</div>
                            @endif
                        </td>
                        <td>{{ $enumLabel($row->status_dalam_keluarga) }}</td>
                        <td class="text-center">{{ $enumLabel($row->kewarganegaraan) }}</td>
                        <td>{{ $row->ayah ?: '-' }}</td>
                        <td>{{ $row->ibu ?: '-' }}</td>
                        <td class="text-center text-nowrap">{{ $fmtDate($row->tgl_mulai_tinggal) ?? '-' }}</td>
                        <td>{{ $row->catatan ?: '-' }}</td>
                    </tr>
                @endforeach
            @empty
                <tr>
                    <td colspan="19" class="text-center" style="padding: 12px;">
                        Tidak ada data KTP dan Kartu Keluarga untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>