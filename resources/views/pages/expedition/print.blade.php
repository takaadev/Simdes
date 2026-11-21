@php
    $fmtDate = fn ($date) => $date
        ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y')
        : null;
@endphp

<x-layouts.print-layout
    title="Buku Ekspedisi"
    paper="A4 landscape"
    fontSize="8.5pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 5%;">NO.<br>URUT</th>
                <th style="width: 14%;">TANGGAL<br>PENGIRIMAN</th>
                <th style="width: 18%;">TANGGAL DAN<br>NOMOR SURAT</th>
                <th style="width: 31%;">ISI SINGKAT SURAT YANG DIKIRIM</th>
                <th style="width: 18%;">DITUJUKAN<br>KEPADA</th>
                <th style="width: 14%;">KETERANGAN</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 6; $i++)
                    <th style="font-size: 7.5pt; padding: 2px;"><em>{{ $i }}</em></th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>
                    <td class="text-center text-nowrap">{{ $fmtDate($row->tanggal_kirim) ?? '-' }}</td>
                    <td>
                        @if ($row->tgl_surat || $row->nomor_surat)
                            @if ($row->tgl_surat)
                                <div>{{ $fmtDate($row->tgl_surat) }}</div>
                            @endif
                            @if ($row->nomor_surat)
                                <div class="fw-bold">{{ $row->nomor_surat }}</div>
                            @endif
                        @else
                            <div class="text-center">-</div>
                        @endif
                    </td>
                    <td>{{ $row->isi_surat ?: '-' }}</td>
                    <td>{{ $row->penerima ?: '-' }}</td>
                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center" style="padding: 12px;">
                        Tidak ada data ekspedisi surat untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>