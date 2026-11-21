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
    title="Buku Lembaran Desa dan Berita Desa"
    paper="A4 landscape"
    fontSize="8.5pt"
    :totalRecords="$records->count()"
    :filterSummary="$filterSummary"
>
    <table class="print-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 4%;">NO.<br>URUT</th>
                <th rowspan="2" style="width: 14%;">JENIS PERATURAN<br>DI DESA</th>
                <th rowspan="2" style="width: 16%;">NOMOR DAN TANGGAL<br>DITETAPKAN</th>
                <th rowspan="2" style="width: 24%;">TENTANG</th>
                <th colspan="2">DIUNDANGKAN</th>
                <th rowspan="2" style="width: 14%;">KET.</th>
            </tr>
            <tr>
                <th style="width: 14%;">TANGGAL</th>
                <th style="width: 14%;">NOMOR</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 7; $i++)
                    <th style="font-size: 7.5pt; padding: 2px;">{{ $i }}</th>
                @endfor
            </tr>
        </thead>

        <tbody>
            @forelse ($records as $index => $row)
                @php
                    $hasLembaran = filled($row->tgl_diundang) || filled($row->nomor_diundang);
                    $hasBerita = filled($row->tgl_berita) || filled($row->nomor_berita);
                    $hasBoth = $hasLembaran && $hasBerita;
                @endphp
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

                    {{-- Kolom 5: Tanggal Diundangkan --}}
                    <td class="text-center">
                        @if ($hasBoth)
                            <div><strong>LD:</strong> {{ $fmtDate($row->tgl_diundang) ?? '-' }}</div>
                            <div><strong>BD:</strong> {{ $fmtDate($row->tgl_berita) ?? '-' }}</div>
                        @elseif ($hasLembaran)
                            {{ $fmtDate($row->tgl_diundang) ?? '-' }}
                        @elseif ($hasBerita)
                            {{ $fmtDate($row->tgl_berita) ?? '-' }}
                        @else
                            -
                        @endif
                    </td>

                    {{-- Kolom 6: Nomor Diundangkan --}}
                    <td class="text-center">
                        @if ($hasBoth)
                            <div><strong>LD:</strong> {{ $row->nomor_diundang ?: '-' }}</div>
                            <div><strong>BD:</strong> {{ $row->nomor_berita ?: '-' }}</div>
                        @elseif ($hasLembaran)
                            {{ $row->nomor_diundang ?: '-' }}
                        @elseif ($hasBerita)
                            {{ $row->nomor_berita ?: '-' }}
                        @else
                            -
                        @endif
                    </td>

                    <td>{{ $row->catatan ?: '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 12px;">
                        Tidak ada data lembaran desa atau berita desa untuk dicetak.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.print-layout>