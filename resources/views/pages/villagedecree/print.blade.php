@php
    $fmtDate = fn ($date) => $date
        ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y')
        : null;
@endphp

<x-layouts.print-layout
    title="Buku Keputusan Kepala Desa"
    paper="A4 landscape"
    fontSize="8.5pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 4%;">NO.<br>URUT</th>
                <th style="width: 18%;">NOMOR DAN TANGGAL<br>KEPUTUSAN KEPALA DESA</th>
                <th style="width: 22%;">TENTANG</th>
                <th style="width: 28%;">URAIAN SINGKAT</th>
                <th style="width: 16%;">NOMOR DAN TANGGAL<br>DILAPORKAN</th>
                <th style="width: 12%;">KET.</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 6; $i++)
                    <th style="font-size: 7.5pt; padding: 2px;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @forelse ($records as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td>
                        @if ($row->nomor_keputusan)
                            <div class="fw-bold">{{ $row->nomor_keputusan }}</div>
                        @endif
                        <div>{{ $fmtDate($row->tgl_keputusan) ?? '-' }}</div>
                    </td>
                    <td>{{ $row->nama_keputusan ?: '-' }}</td>
                    <td>{{ $row->uraian ?: '-' }}</td>
                    <td>
                        @if ($row->nomor_dilaporkan || $row->tgl_dilaporkan)
                            @if ($row->nomor_dilaporkan)
                                <div class="fw-bold">{{ $row->nomor_dilaporkan }}</div>
                            @endif
                            @if ($row->tgl_dilaporkan)
                                <div>{{ $fmtDate($row->tgl_dilaporkan) }}</div>
                            @endif
                        @else
                            <div class="text-center">-</div>
                        @endif
                    </td>
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 12px;">
                        Tidak ada data keputusan kepala desa untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>