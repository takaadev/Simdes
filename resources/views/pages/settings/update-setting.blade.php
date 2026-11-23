<x-action-edit id="updateProfileDesa" variant="button" label="Profil" modalTitle="Profil Desa" color="btn-simdes-primary"
    size="lg" href="#">
    <x-fieldset label="Perbarui Informasi Profil Desa">
        <div class="row">
            {{-- Identitas & Wilayah --}}
            <div class="col-12 col-md-6">
                <x-input-text name="nama_desa" label="Nama Desa" required />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="kode_wilayah" label="Kode Wilayah" required />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="kecamatan" label="Kecamatan" required />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="kabupaten" label="Kabupaten" required />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="provinsi" label="Provinsi" required />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="kode_pos" label="Kode Pos" required />
            </div>
            <div class="col-12">
                <x-input-text name="alamat_kantor" label="Alamat Kantor" required />
            </div>

            {{-- Kontak --}}
            <div class="col-12 col-md-6">
                <x-input-text name="telepon" label="Telepon" placeholder="Contoh: 081234567890" />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text type="email" name="email" label="Email" placeholder="Contoh: desa@example.com" />
            </div>

            {{-- Perangkat & Struktur --}}
            <div class="col-12 col-md-6">
                <x-input-text name="nama_kades" label="Kepala Desa" />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="nama_sekdes" label="Sekretaris Desa" />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text type="number" name="total_rw" label="Jumlah RW" min="0" required />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text type="number" name="total_rt" label="Jumlah RT" min="0" required />
            </div>

            {{-- Operasional --}}
            <div class="col-12">
                <x-input-text name="jam_operasional" label="Jam Layanan Kantor"
                    placeholder="Contoh: Sen–Kam 08.00–15.30 WITA" />
            </div>
        </div>
    </x-fieldset>
</x-action-edit>
