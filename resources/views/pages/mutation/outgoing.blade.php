<x-action-create href="{{ route('mutations.outgoing') }}" modalTitle="Mutasi Penduduk Keluar" label="Mutasi Keluar"
    color="btn-simdes-excel" icon="right-from-bracket">
    <x-fieldset label="Informasi Mutasi Keluar">
        <div class="row g-3">

            <div class="col-12">
                <x-input-select2 name="resident_id" label="Nama Penduduk" :options="\App\Support\ResidentOptions::options()" placeholder="Pilih penduduk"
                    required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="kategori_mutasi" label="Kategori Mutasi" :options="\App\Enums\MutationCategoryList::options('DEATH,OUTGOING')"
                    placeholder="Pilih kategori mutasi" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_mutasi" label="Tanggal Mutasi" required />
            </div>

            <div class="col-12">
                <x-input-text name="tujuan_mutasi" label="Asal / Tujuan Mutasi"
                    placeholder="Contoh: Bangko atau wilayah tujuan lainnya" maxlength="255" />
                <small class="text-muted">
                    Wajib diisi apabila kategori mutasi adalah Penduduk Keluar.
                </small>
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan"
                    placeholder="Masukkan keterangan tambahan mengenai mutasi penduduk..."
                    hint="Informasi tambahan yang diperlukan dalam administrasi kependudukan." rows="3" />
            </div>
        </div>
    </x-fieldset>
</x-action-create>
