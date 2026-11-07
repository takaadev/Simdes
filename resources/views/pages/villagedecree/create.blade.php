<x-action-create href="{{ route('village-decrees.store') }}" size="xl">

    {{-- A. Informasi Keputusan --}}
    <x-fieldset label="A. Informasi Keputusan">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-text name="nomor_keputusan" label="Nomor Keputusan"
                    hint="Nomor resmi keputusan kepala desa sesuai urutan administrasi dan tahun penerbitan."
                    placeholder="Contoh: 09/KEP-CONTOH/XI/2026" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text type="date" name="tgl_keputusan" label="Tanggal Ditetapkan"
                    hint="Tanggal keputusan ditandatangani atau resmi ditetapkan oleh kepala desa." required />
            </div>

            <div class="col-12">
                <x-input-text name="nama_keputusan" label="Judul atau Tentang Keputusan"
                    placeholder="Masukkan judul atau pokok materi keputusan"
                    hint="Tuliskan judul atau pokok kebijakan yang menjadi dasar penetapan keputusan." required />
            </div>

            <div class="col-12">
                <x-input-textarea name="uraian" label="Uraian Keputusan"
                    placeholder="Tuliskan ringkasan substansi pokok keputusan..."
                    hint="Jelaskan isi, tujuan, dasar pertimbangan, serta ruang lingkup keputusan secara ringkas."
                    :rows="4" />
            </div>
        </div>
    </x-fieldset>

    {{-- B. Informasi Pelaporan --}}
    <x-fieldset label="B. Informasi Pelaporan">
        <div class="row g-3">
            <div class="col-12 col-md-4">
                <x-input-text name="nomor_dilaporkan" label="Nomor Pelaporan" placeholder="Masukkan nomor pelaporan"
                    hint="Nomor surat atau bukti administrasi pelaporan keputusan kepada instansi terkait." />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_dilaporkan" label="Tanggal Dilaporkan"
                    hint="Tanggal keputusan disampaikan atau dilaporkan kepada instansi terkait." />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="status" label="Status Keputusan" :options="\App\Enums\DecreeStatusList::options()"
                    placeholder="-- Pilih Status Keputusan --"
                    hint="Tentukan status administrasi keputusan sesuai tahapan pengelolaannya." />
            </div>
        </div>
    </x-fieldset>

    {{-- C. Dokumen dan Catatan --}}
    <x-fieldset label="C. Dokumen dan Catatan">
        <div class="row g-3">
            <div class="col-12">
                <x-input-text type="file" name="dokumen" label="Dokumen Keputusan" accept=".pdf,application/pdf"
                    hint="Unggah salinan dokumen keputusan resmi dalam format PDF. Pastikan dokumen terbaca dengan jelas." />
            </div>

            <div class="col-12">
                <x-input-textarea name="catatan" label="Catatan" placeholder="Tambahkan catatan jika diperlukan..."
                    hint="Tambahkan informasi atau keterangan lain yang dianggap penting terkait keputusan."
                    :rows="3" />
            </div>
        </div>
    </x-fieldset>

</x-action-create>
