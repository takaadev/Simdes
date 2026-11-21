@php
    $fmtDate = fn ($date) => $date
        ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y')
        : null;

    $enumLabel = function ($value): string {
        if ($value instanceof \BackedEnum) {
            return method_exists($value, 'label') ? $value->label() : (string) $value->value;
        }

        return filled($value) ? (string) $value : '-';
    };
@endphp

<x-layouts.print-layout
    title="Buku Aparat Pemerintah Desa"
    paper="A4 landscape"
    fontSize="7.5pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 3%;">NO.<br>URUT</th>
                <th style="width: 12%;">NAMA</th>
                <th style="width: 8%;">NIAP</th>
                <th style="width: 8%;">NIP</th>
                <th style="width: 6%;">JENIS<br>KELAMIN</th>
                <th style="width: 10%;">TEMPAT DAN TGL<br>LAHIR</th>
                <th style="width: 5%;">AGAMA</th>
                <th style="width: 7%;">PANGKAT<br>GOLONGAN</th>
                <th style="width: 9%;">JABATAN</th>
                <th style="width: 7%;">PENDIDIKAN<br>TERAKHIR</th>
                <th style="width: 10%;">NOMOR DAN TANGGAL<br>KEPUTUSAN<br>PENGANGKATAN</th>
                <th style="width: 10%;">NOMOR DAN TANGGAL<br>KEPUTUSAN<br>PEMBERHENTIAN</th>
                <th style="width: 5%;">KET.</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 13; $i++)
                    <th style="font-size: 7pt; padding: 2px;"><em>{{ $i }}</em></th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $row->nama ?: '-' }}</td>
                    <td class="text-center text-nowrap">{{ $row->niap ?: '-' }}</td>
                    <td class="text-center text-nowrap">{{ $row->nip ?: '-' }}</td>
                    <td class="text-center">{{ $enumLabel($row->jenis_kelamin) }}</td>
                    <td>
                        {{ $row->tempat_lahir ?: '-' }}@if ($row->tgl_lahir),<br>{{ $fmtDate($row->tgl_lahir) }}@endif
                    </td>
                    <td class="text-center">{{ $enumLabel($row->agama ?? '-') }}</td>
                    <td class="text-center">{{ $row->pangkat_golongan ?: '-' }}</td>
                    <td>{{ $row->position?->nama ?? '-' }}</td>
                    <td class="text-center">{{ $enumLabel($row->pendidikan) }}</td>
                    <td>
                        @if ($row->nomor_pengangkatan || $row->tgl_pengangkatan)
                            @if ($row->nomor_pengangkatan)
                                <div class="fw-bold">{{ $row->nomor_pengangkatan }}</div>
                            @endif
                            @if ($row->tgl_pengangkatan)
                                <div>{{ $fmtDate($row->tgl_pengangkatan) }}</div>
                            @endif
                        @else
                            <div class="text-center">-</div>
                        @endif
                    </td>
                    <td>
                        @if ($row->nomor_pemberhentian || $row->tgl_pemberhentian)
                            @if ($row->nomor_pemberhentian)
                                <div class="fw-bold">{{ $row->nomor_pemberhentian }}</div>
                            @endif
                            @if ($row->tgl_pemberhentian)
                                <div>{{ $fmtDate($row->tgl_pemberhentian) }}</div>
                            @endif
                        @else
                            <div class="text-center">-</div>
                        @endif
                    </td>
                    <td>{{ $row->notes ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13" class="text-center" style="padding: 12px;">
                        Tidak ada data aparat pemerintah desa untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>