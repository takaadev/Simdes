<x-action-show :id="$data->id" modalTitle="Detail Kartu Keluarga" size="fullscreen">

    {{-- A. Informasi Kartu Keluarga --}}
    <x-fieldset label="A. Informasi Kartu Keluarga">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_kk"
                    label="Nomor Kartu Keluarga"
                    value="{{ $data->nomor_kk }}"
                    disabled
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text
                    name="jumlah_anggota"
                    label="Jumlah Anggota Keluarga"
                    value="{{ $data->residents?->count() ?? 0 }} Orang"
                    disabled
                />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Daftar Anggota Keluarga --}}
    <x-fieldset label="B. Daftar Anggota Keluarga">

        <div class="table-responsive wide-scroll-ledger">
            <table class="table table-bordered table-sm simdes-ledger-table align-middle mb-0">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center">No.</th>
                        <th rowspan="2">Nama Lengkap</th>
                        <th rowspan="2">NIK</th>
                        <th rowspan="2" class="text-center">Jenis Kelamin</th>
                        <th colspan="2" class="text-center">Tempat &amp; Tanggal Lahir</th>
                        <th rowspan="2" class="text-center">Gol. Darah</th>
                        <th rowspan="2">Agama</th>
                        <th rowspan="2">Pendidikan</th>
                        <th rowspan="2">Pekerjaan</th>
                        <th rowspan="2">Alamat</th>
                        <th rowspan="2">Status Perkawinan</th>
                        <th colspan="2" class="text-center">Tempat &amp; Tanggal Dikeluarkan E-KTP</th>
                        <th rowspan="2">Kedudukan dalam Keluarga</th>
                        <th rowspan="2">Kewarganegaraan</th>
                        <th colspan="2" class="text-center">Orang Tua</th>
                        <th rowspan="2">Mulai Tinggal</th>
                    </tr>
                    <tr>
                        <th>Tempat</th>
                        <th>Tanggal</th>
                        <th>Tempat</th>
                        <th>Tanggal</th>
                        <th>Ayah</th>
                        <th>Ibu</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse (($data->residents ?? collect()) as $index => $resident)
    <tr>
        <td class="text-center">{{ $index + 1 }}</td>

        <td>{{ $resident->nama_lengkap ?: '-' }}</td>

        <td>{{ $resident->nik ?: '-' }}</td>

        {{-- Jenis Kelamin --}}
        <td class="text-center">
            {{ $resident->jenis_kelamin instanceof \BackedEnum
                ? $resident->jenis_kelamin->label()
                : ($resident->jenis_kelamin ?: '-') }}
        </td>

        <td>{{ $resident->tempat_lahir ?: '-' }}</td>

        {{-- Tanggal Lahir --}}
        <td class="text-center">
            {{ $resident->tgl_lahir
                ? \Illuminate\Support\Carbon::parse($resident->tgl_lahir)->format('d-m-Y')
                : '-' }}
        </td>

        {{-- Golongan Darah --}}
        <td class="text-center">
            {{ $resident->gol_darah instanceof \BackedEnum
                ? $resident->gol_darah->label()
                : ($resident->gol_darah ?: '-') }}
        </td>

        {{-- Agama --}}
        <td>
            {{ $resident->agama instanceof \BackedEnum
                ? $resident->agama->label()
                : ($resident->agama ?: '-') }}
        </td>

        {{-- Pendidikan --}}
        <td>
            {{ $resident->pendidikan instanceof \BackedEnum
                ? $resident->pendidikan->label()
                : ($resident->pendidikan ?: '-') }}
        </td>

        {{-- Pekerjaan --}}
        <td>
            {{ $resident->pekerjaan instanceof \BackedEnum
                ? $resident->pekerjaan->label()
                : ($resident->pekerjaan ?: '-') }}
        </td>

        {{-- Alamat --}}
        <td>
            {{ $resident->alamat ?: '-' }}

            @if ($resident->rt || $resident->rw)
                <br>
                <small class="text-muted">
                    RT {{ $resident->rt instanceof \BackedEnum
                        ? $resident->rt->value
                        : ($resident->rt ?: '-') }}

                    /

                    RW {{ $resident->rw instanceof \BackedEnum
                        ? $resident->rw->value
                        : ($resident->rw ?: '-') }}
                </small>
            @endif
        </td>

        {{-- Status Perkawinan --}}
        <td>
            {{ $resident->status_perkawinan instanceof \BackedEnum
                ? $resident->status_perkawinan->label()
                : ($resident->status_perkawinan ?: '-') }}
        </td>

        <td>{{ $resident->tempat_dikeluarkan ?: '-' }}</td>

        {{-- Tanggal Dikeluarkan --}}
        <td class="text-center">
            {{ $resident->tgl_dikeluarkan
                ? \Illuminate\Support\Carbon::parse($resident->tgl_dikeluarkan)->format('d-m-Y')
                : '-' }}
        </td>

        {{-- Kedudukan dalam Keluarga --}}
        <td>
            {{ $resident->status_dalam_keluarga instanceof \BackedEnum
                ? $resident->status_dalam_keluarga->label()
                : ($resident->status_dalam_keluarga ?: '-') }}
        </td>

        {{-- Kewarganegaraan --}}
        <td>
            {{ $resident->kewarganegaraan instanceof \BackedEnum
                ? $resident->kewarganegaraan->label()
                : ($resident->kewarganegaraan ?: '-') }}
        </td>

        <td>{{ $resident->ayah ?: '-' }}</td>

        <td>{{ $resident->ibu ?: '-' }}</td>

        {{-- Mulai Tinggal --}}
        <td class="text-center">
            {{ $resident->tgl_mulai_tinggal
                ? \Illuminate\Support\Carbon::parse($resident->tgl_mulai_tinggal)->format('d-m-Y')
                : '-' }}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="19" class="text-center text-muted py-4">
            Belum ada anggota keluarga yang terdaftar.
        </td>
    </tr>
@endforelse
                </tbody>
            </table>
        </div>

    </x-fieldset>

</x-action-show>