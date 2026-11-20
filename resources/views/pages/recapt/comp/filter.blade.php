<x-filters :url="route('recapts.index')" table="#residentRecaptTable">
    <x-slot:filter>
        {{-- Filter Tahun --}}
        <x-filter-item label="Tahun" id="filterYearSelect" name="tahun" :options="\App\Enums\ListYear::options()"
            value="{{ (string) now()->year }}" placeholder="Pilih Tahun" />

        {{-- Filter Bulan --}}
        <x-filter-item label="Bulan" id="filterMonthSelect" name="bulan" :options="[
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
            value="{{ now()->format('m') }}" placeholder="Pilih Bulan" />
    </x-slot:filter>

    <x-slot:actions>
        <button type="button" id="btnCetakRecapt" class="btn-simdes-outline">
            <i class="fa-solid fa-print" aria-hidden="true"></i>
            <span>Cetak</span>
        </button>
        @include('pages.recapt.create')
    </x-slot:actions>
</x-filters>
