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
    title="Buku Peraturan di Desa"
    paper="A4 landscape"
    fontSize="8pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 3%;">NO.<br>URUT</th>
                <th style="width: 10%;">JENIS PERATURAN<br>DI DESA</th>
                <th style="width: 12%;">NOMOR DAN TANGGAL<br>DITETAPKAN</th>
                <th style="width: 14%;">TENTANG</th>
                <th style="width: 16%;">URAIAN SINGKAT</th>
                <th style="width: 9%;">TANGGAL<br>KESEPAKATAN</th>
                <th style="width: 11%;">NOMOR DAN TANGGAL<br>DILAPORKAN</th>
                <th style="width: 11%;">DIUNDANGKAN DALAM<br>LEMBARAN DESA</th>
                <th style="width: 10%;">DIUNDANGKAN DALAM<br>BERITA DESA</th>
                <th style="width: 4%;">KET.</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 10; $i++)
                    <th style="font-size: 7pt; padding: 2px;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>{{ $enumLabel($row->jenis_peraturan) }}</td>
                    <td>
                        @if ($row->nomor_peraturan)
                            <div class="fw-bold">{{ $row->nomor_peraturan }}</div>
                        @endif
                        <div>{{ $fmtDate($row->tgl_ditetapkan) ?? '-' }}</div>
                    </td>
                    <td>{{ $row->nama_peraturan ?: '-' }}</td>
                    <td>{{ $row->uraian ?: '-' }}</td>
                    <td class="text-center">{{ $fmtDate($row->tgl_kesepakatan) ?? '-' }}</td>
                    <td>
                        @if ($row->nomor_dilaporkan || $row->tgl_dilaporkan)
                            @if ($row->nomor_dilaporkan)
                                <div class="fw-bold">{{ $row->nomor_dilaporkan }}</div>
                            @endif
                            @if ($row->tgl_dilaporkan)
                                <div>{{ $fmtDate($row->tgl_dilaporkan) }}</div>
                            @endif
                        @else
                            <span class="text-center d-block">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($row->nomor_diundang || $row->tgl_diundang)
                            @if ($row->nomor_diundang)
                                <div class="fw-bold">{{ $row->nomor_diundang }}</div>
                            @endif
                            @if ($row->tgl_diundang)
                                <div>{{ $fmtDate($row->tgl_diundang) }}</div>
                            @endif
                        @else
                            <span class="text-center d-block">-</span>
                        @endif
                    </td>
                    <td>
                        @if ($row->nomor_berita || $row->tgl_berita)
                            @if ($row->nomor_berita)
                                <div class="fw-bold">{{ $row->nomor_berita }}</div>
                            @endif
                            @if ($row->tgl_berita)
                                <div>{{ $fmtDate($row->tgl_berita) }}</div>
                            @endif
                        @else
                            <span class="text-center d-block">-</span>
                        @endif
                    </td>
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding: 12px;">
                        Tidak ada data peraturan desa untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>