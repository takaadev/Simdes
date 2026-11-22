<x-action-create href="{{ route('village-officials.store') }}" size="lg">
    <x-fieldset label="A. Identitas Aparat">
        <div class="row g-3">

            <div class="col-12 col-md-8">
                <x-input-text name="nama" label="Nama Lengkap" placeholder="Contoh: Arman Saputra, S.AP" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select name="village_position_id" label="Jabatan" :options="\App\Models\VillagePosition::query()
                    ->where('is_active', true)
                    ->orderBy('kode')
                    ->pluck('nama', 'id')
                    ->toArray()" placeholder="Pilih Jabatan"
                    required />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="pangkat_golongan" label="Pangkat/Gol" placeholder="Contoh:Penata Muda Tingkat I (III/b)" maxlength="16" required />
            </div>
            <div class="col-12 col-md-6">
                <x-input-text name="niap" label="NIAP" placeholder="Masukkan NIAP"
                    hint="Nomor Induk Aparat Pemerintah Desa." maxlength="16" required />
            </div>

            <div class="col-12 col-md-6">
                <x-input-text name="nip" label="NIP" placeholder="Masukkan NIP"
                    hint="Kosongkan apabila bukan PNS." maxlength="16" />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="jenis_kelamin" label="Jenis Kelamin" :options="\App\Enums\GenderList::options()" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="tempat_lahir" label="Tempat Lahir" placeholder="Contoh: Bangko" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_lahir" label="Tanggal Lahir" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="agama" label="Agama" :options="\App\Enums\ReligionList::options()" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="pendidikan" label="Pendidikan Terakhir" :options="\App\Enums\EducationList::options()" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text name="nomor_pengangkatan" label="Nomor Keputusan Pengangkatan"
                    placeholder="Contoh: 02/KEP/DS/2026" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="date" name="tgl_pengangkatan" label="Tanggal Keputusan Pengangkatan" required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-select name="status" label="Status Aparat" :options="\App\Enums\VillageOfficialStatusList::options()" placeholder="Pilih Status"
                    required />
            </div>

            <div class="col-12 col-md-4">
                <x-input-text type="tel" name="telpon" label="Nomor Telepon" placeholder="Contoh: 081234567890"
                    maxlength="30" required />
            </div>

            <div class="col-12">
                <x-input-text name="alamat" label="Alamat" placeholder="Masukkan alamat lengkap aparat desa..."
                    required />
            </div>

            <div class="col-12">
                <x-input-textarea name="notes" label="Catatan" placeholder="Catatan tambahan terkait aparat desa..."
                    hint="Informasi tambahan mengenai kepegawaian atau penugasan." :rows="3" />
            </div>

            {{-- Checkbox Jadikan Akun Login --}}
            <div class="col-12 col-md-6">
                <div class="mt-4 p-2 rounded-3" style="background: #d3eed3; border: 1px solid rgb(7, 165, 31);">
                    <div class="form-check d-flex align-items-start gap-2 mb-0">
                        <input type="hidden" name="has_user" value="0">
                        <input class="form-check-input mt-1" type="checkbox" id="has_user" name="has_user"
                            value="1">
                        <label class="form-check-label" for="has_user" style="cursor: pointer;">
                            Centang, jadikan akun login.
                        </label>
                    </div>
                </div>
            </div>

            {{-- Ditampilkan hanya jika checkbox dicentang --}}
            <div class="col-12 col-md-6 d-none" id="roleFieldWrapper">
                <x-input-select name="role" label="Hak Akses" :options="\App\Enums\RoleAccessType::options()" placeholder="Pilih Hak Akses" />
            </div>

        </div>
    </x-fieldset>
</x-action-create>

@push('footer')
    <script>
        $(function() {
            const $hasUserCheckbox = $('#has_user');
            const $roleWrapper = $('#roleFieldWrapper');
            const $roleSelect = $roleWrapper.find('select[name="role"]');

            if (!$hasUserCheckbox.length || !$roleWrapper.length) return;

            function syncRoleVisibility() {
                const isChecked = $hasUserCheckbox.is(':checked');

                if (isChecked) {
                    $roleWrapper.removeClass('d-none');
                    $roleSelect.prop('disabled', false).prop('required', true);
                } else {
                    $roleWrapper.addClass('d-none');
                    $roleSelect.val('').prop('required', false).prop('disabled', true);
                }
            }

            $hasUserCheckbox.on('change', syncRoleVisibility);

            // Reset tampilan saat modal create ditutup
            $hasUserCheckbox.closest('.modal').on('hidden.bs.modal', function() {
                $hasUserCheckbox.prop('checked', false);
                syncRoleVisibility();
            });

            // Inisialisasi status awal
            syncRoleVisibility();
        });
    </script>
@endpush
