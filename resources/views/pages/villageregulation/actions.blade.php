{{-- <div class="ket-cell-content">
    <div class="row-action-group no-print" role="group" aria-label="Aksi baris peraturan">
        <a href="{{ route('village-regulations.show', $row->id) }}" class="btn-row-action" title="Lihat Detail"
            aria-label="Lihat detail peraturan">
            <i class="fa-regular fa-eye" aria-hidden="true"></i>
        </a>

        <a href="{{ route('village-regulations.edit', $row->id) }}" class="btn-row-action" title="Edit Peraturan"
            aria-label="Edit peraturan">
            <i class="fa-regular fa-pen-to-square" aria-hidden="true"></i>
        </a>

        <button type="button" class="btn-row-action action-delete" data-id="{{ $row->id }}"
            title="Hapus Peraturan" aria-label="Hapus peraturan">
            <i class="fa-regular fa-trash-can" aria-hidden="true"></i>
        </button>

    </div>
</div> --}}
<x-row-action>
    <li>
        <a class="dropdown-item" href="#">
            <i class="fa-solid fa-eye"></i>
            <span>Detail</span>
        </a>
    </li>

    <li>
        <a class="dropdown-item" href="#">
            <i class="fa-solid fa-pen-to-square"></i>
            <span>Edit</span>
        </a>
    </li>

    <li>
        <hr class="dropdown-divider">
    </li>

    <li>
        <a class="dropdown-item text-danger" href="#">
            <i class="fa-solid fa-trash-can"></i>
            <span>Hapus</span>
        </a>
    </li>
</x-row-action>
