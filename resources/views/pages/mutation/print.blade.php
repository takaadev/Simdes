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
    title="Buku Mutasi Penduduk Desa"
    paper="A4 landscape"
    fontSize="7.5pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">NOMOR<br>URUT</th>
                <th rowspan="2" style="width: 14%;">NAMA LENGKAP /<br>PANGGILAN</th>
                <th colspan="2">TEMPAT &amp; TANGGAL LAHIR</th>
                <th rowspan="2" style="width: 4.5%;">JENIS<br>KELAMIN</th>
                <th rowspan="2" style="width: 6%;">KEWARGA-<br>NEGARAAN</th>
                <th colspan="2">PENAMBAHAN</th>
                <th colspan="4">PENGURANGAN</th>
                <th rowspan="2" style="width: 9%;">KET.</th>
            </tr>
            <tr>
                <th style="width: 8%;">TEMPAT</th>
                <th style="width: 6.5%;">TANGGAL</th>
                <th style="width: 10%;">DATANG DARI</th>
                <th style="width: 6.5%;">TANGGAL</th>
                <th style="width: 10%;">PINDAH KE</th>
                <th style="width: 6.5%;">TANGGAL</th>
                <th style="width: 9.5%;">MENINGGAL</th>
                <th style="width: 6.5%;">TANGGAL</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 13; $i++)
                    <th style="font-size: 7pt; padding: 2px;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                @php
                    $tipe = $enumValue($row->tipe_mutasi);
                    $kategori = $enumValue($row->kategori_mutasi);

                    $isAdd = $tipe === 'add';
                    $isOutgoing = $tipe === 'minus' && $kategori === 'outgoing';
                    $isDeath = $tipe === 'minus' && $kategori === 'death';

                    $datangDari = '-';
                    if ($isAdd) {
                        $datangDari = $kategori === 'birth'
                            ? 'Kelahiran'
                            : ($row->tujuan_mutasi ?: '-');
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $row->resident?->nama_lengkap ?? '-' }}</td>
                    <td>{{ $row->resident?->tempat_lahir ?: '-' }}</td>
                    <td class="text-center text-nowrap">{{ $fmtDate($row->resident?->tgl_lahir) }}</td>
                    <td class="text-center">{{ $enumValue($row->resident?->jenis_kelamin) ?: '-' }}</td>
                    <td class="text-center">{{ $enumLabel($row->resident?->kewarganegaraan) }}</td>

                    {{-- Penambahan --}}
                    <td>{{ $datangDari }}</td>
                    <td class="text-center text-nowrap">{{ $isAdd ? $fmtDate($row->tgl_mutasi) : '-' }}</td>

                    {{-- Pengurangan: Pindah --}}
                    <td>{{ $isOutgoing ? ($row->tujuan_mutasi ?: '-') : '-' }}</td>
                    <td class="text-center text-nowrap">{{ $isOutgoing ? $fmtDate($row->tgl_mutasi) : '-' }}</td>

                    {{-- Pengurangan: Meninggal --}}
                    <td class="text-center">{{ $isDeath ? 'Meninggal' : '-' }}</td>
                    <td class="text-center text-nowrap">{{ $isDeath ? $fmtDate($row->tgl_mutasi) : '-' }}</td>

                    {{-- Keterangan --}}
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center" style="padding: 12px;">
                        Tidak ada data mutasi penduduk untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>