
<x-action-edit :id="$data->id" href="{{ route('agendas.update', $data->id) }}" size="lg"
    modalTitle="Edit Surat Keluar" label="Edit Surat Keluar">

    <x-input-text value="outgoing" name="tipe_agenda" type="hidden" />

    {{-- Kategori tetap dikirim meskipun select disabled --}}
    <x-input-text
        name="kategori_agenda"
        value="{{ $data->kategori_agenda?->value ?? $data->kategori_agenda }}"
        type="hidden"
    />

    <x-fieldset label="A. Informasi Surat Keluar">
        <div class="row g-3">

            {{-- Kategori Surat --}}
            <div class="col-12 col-md-6">
                <x-input-select
                    name="kategori_agenda_display"
                    label="Kategori Surat"
                    :options="\App\Enums\AgendaCategoryList::options()"
                    value="{{ $data->kategori_agenda?->value ?? $data->kategori_agenda }}"
                    disabled
                />
            </div>

            {{-- Nomor Surat --}}
            <div class="col-12 col-md-6">
                <x-input-text
                    name="nomor_surat"
                    label="Nomor Surat"
                    value="{{ $data->nomor_surat }}"
                    required
                />
            </div>

            {{-- Tanggal Surat --}}
            <div class="col-12 col-md-3">
                <x-input-text
                    type="date"
                    name="tgl_surat"
                    label="Tanggal Surat"
                    value="{{ $data->tgl_surat?->format('Y-m-d') }}"
                    required
                />
            </div>

            {{-- Tanggal Kirim --}}
            <div class="col-12 col-md-3">
                <x-input-text
                    type="date"
                    name="tanggal_kirim"
                    label="Tanggal Kirim"
                    value="{{ $data->tanggal_kirim?->format('Y-m-d') }}"
                    required
                />
            </div>

            {{-- Tujuan --}}
            <div class="col-12 col-md-6">
                <x-input-text
                    name="penerima"
                    label="Tujuan / Penerima Surat"
                    value="{{ $data->penerima }}"
                    placeholder="Nama instansi atau pihak penerima"
                    required
                />
            </div>

            {{-- Perihal --}}
            <div class="col-12">
                <x-input-textarea
                    name="isi_surat"
                    label="Perihal Surat"
                    value="{{ $data->isi_surat }}"
                    placeholder="Tuliskan perihal atau ringkasan isi surat..."
                    :rows="3"
                    required
                />
            </div>

        </div>
    </x-fieldset>
</x-action-edit>
