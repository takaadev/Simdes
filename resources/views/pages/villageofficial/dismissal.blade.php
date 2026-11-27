<x-action-create icon="user-x" color="btn-simdes-accent" label="Pemberhentian" modalTitle="Pemberhentian Aparat Desa" icon="file-circle-xmark" href="{{ route('village-officials.dismissal') }}">
    <x-fieldset label="Formulir Pemberhentian Aparat Desa">
        <div class="row g-3">

            <div class="col-12">
                <x-input-select2 name="official_id" label="Pilih Aparat" :options="\App\Support\DismissalOfficialVillageOptions::options()" required />
            </div>

            <div class="col-12">
                <x-input-text name="nomor_pemberhentian" label="Nomor Keputusan Pemberhentian"
                    placeholder="Contoh: 02/KEP/DS/2026" required />
            </div>

            <div class="col-12">
                <x-input-text type="date" name="tgl_pemberhentian" label="Tanggal Keputusan Pemberhentian"
                    required />
            </div>

            <div class="col-12">
                <x-input-select name="status" label="Status Pemberhentian" :options="\App\Enums\VillageOfficialStatusList::options()" placeholder="Pilih Status"
                    required />
            </div>

            <div class="col-12">
                <x-input-textarea name="notes" label="Catatan"
                    placeholder="Catatan tambahan terkait pemberhentian aparat desa..."
                    hint="Informasi tambahan mengenai alasan atau keterangan pemberhentian." :rows="3" />
            </div>

        </div>
    </x-fieldset>
</x-action-create>
