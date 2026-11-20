<x-action-create label="Rekapitulasi" submitLable="Mulai Rekap" modalTitle="Rekap Penduduk" icon="book-open-reader" href="{{ route('recapts.store') }}">
    <x-fieldset label="Periode Rekapitulasi">
        <div class="row g-3">
            <div class="col-12 col-md-6">
                <x-input-select
                    name="bulan"
                    label="Bulan"
                    :options="[
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ]"
                    value="{{ now()->format('m') }}"
                    placeholder="Pilih Bulan"
                    required
                />
            </div>

            <div class="col-12 col-md-6">
                <x-input-select
                    name="tahun"
                    label="Tahun"
                    :options="collect(range(now()->year, now()->year - 10))
                        ->mapWithKeys(fn ($year) => [(string) $year => (string) $year])
                        ->all()"
                    value="{{ (string) now()->year }}"
                    placeholder="Pilih Tahun"
                    required
                />
            </div>

            <div class="col-12">
                <div class="alert alert-info border-0 d-flex align-items-start gap-2 small mb-0" role="alert">
                    <i class="fa-solid fa-circle-info mt-1" aria-hidden="true"></i>
                    <div>
                        <strong>Informasi:</strong> Penduduk akan direkap otomatis oleh sistem setiap
                        <strong>tanggal 5</strong> untuk periode bulan lalu jika belum direkap secara manual.
                        Rekap manual akan menghitung otomatis per RT dan memperbarui data bila periode yang dipilih sudah ada.
                    </div>
                </div>
            </div>
        </div>
    </x-fieldset>
</x-action-create>