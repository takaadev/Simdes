@php
    $fmtDate = fn ($date) => $date
        ? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d/m/Y')
        : '-';
@endphp

<x-layouts.print-layout
    title="Buku Agenda"
    paper="330mm 215mm"
    fontSize="7.5pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 3%;">NO.</th>
                <th rowspan="2" style="width: 7.5%;">TANGGAL<br>PENERIMAAN</th>
                <th colspan="4">SURAT MASUK</th>
                <th rowspan="2" style="width: 7.5%;">TANGGAL<br>PENGIRIMAN</th>
                <th colspan="4">SURAT KELUAR</th>
            </tr>
            <tr>
                <th style="width: 10%;">NOMOR</th>
                <th style="width: 7%;">TANGGAL</th>
                <th style="width: 11%;">PENGIRIM</th>
                <th style="width: 13%;">ISI SINGKAT</th>
                <th style="width: 10%;">NOMOR</th>
                <th style="width: 7%;">TANGGAL</th>
                <th style="width: 11%;">TUJUAN</th>
                <th style="width: 13%;">ISI SINGKAT</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 11; $i++)
                    <th style="font-size: 7pt; padding: 2px;"><em>{{ $i }}</em></th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $pair)
                @php
                    $in = $pair->incoming;
                    $out = $pair->outgoing;
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}.</td>

                    {{-- Surat Masuk --}}
                    <td class="text-center text-nowrap">{{ $in ? $fmtDate($in->tanggal_terima) : '-' }}</td>
                    <td>{{ $in?->nomor_surat ?: '-' }}</td>
                    <td class="text-center text-nowrap">{{ $in ? $fmtDate($in->tgl_surat) : '-' }}</td>
                    <td>{{ $in?->pengirim ?: '-' }}</td>
                    <td>{{ $in?->isi_surat ?: '-' }}</td>

                    {{-- Surat Keluar --}}
                    <td class="text-center text-nowrap">{{ $out ? $fmtDate($out->tanggal_kirim) : '-' }}</td>
                    <td>{{ $out?->nomor_surat ?: '-' }}</td>
                    <td class="text-center text-nowrap">{{ $out ? $fmtDate($out->tgl_surat) : '-' }}</td>
                    <td>{{ $out?->penerima ?: '-' }}</td>
                    <td>{{ $out?->isi_surat ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="padding: 12px;">
                        Tidak ada data agenda surat untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>