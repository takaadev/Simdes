@forelse ($data as $row)
    <tr>
        {{-- Nomor urut --}}
        <td class="text-center">
            {{ $loop->iteration }}
        </td>

        {{-- Nomor Register --}}
        <td>
            {{ $row->register_number }}
        </td>

        {{-- Jenis Peraturan --}}
        <td>
            {{ $row->regulation_type?->label() ?? '-' }}
        </td>

        {{-- Nomor Peraturan --}}
        <td>
            {{ $row->regulation_number }}
        </td>

        {{-- Tentang --}}
        <td>
            {{ $row->regulation_title }}
        </td>

        {{-- Uraian Singkat --}}
        <td>
            {{ $row->subject ?? '-' }}
        </td>

        {{-- Tanggal Penetapan --}}
        <td>
            {{ $row->stipulation_date?->locale('id')->translatedFormat('d F Y') ?? '-' }}
        </td>

        {{-- Tanggal Pengundangan --}}
        <td>
            {{ $row->promulgation_date?->locale('id')->translatedFormat('d F Y') ?? '-' }}
        </td>

        {{-- Status --}}
        <td>
            {{ $row->status }}
        </td>

        {{-- Keterangan --}}
        <td>
            {{ $row->notes ?? '-' }}
        </td>

        {{-- Aksi --}}
        <td class="text-center">
            @include('pages.villageregulation.actions', [
                'row' => $row,
            ])
        </td>
    </tr>
@empty
    <tr>
        <td colspan="11" class="text-center py-4">
            Belum ada data peraturan desa.
        </td>
    </tr>
@endforelse
