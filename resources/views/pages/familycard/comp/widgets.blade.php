<x-widgets label="Ringkasan Buku Kartu Keluarga">
    <x-widget-item id="statTotal" label="Total Penduduk Terdaftar" value="0" icon="users" color="blue" />

    <x-widget-item id="statMale" label="Jumlah Laki-laki" value="0" icon="mars" color="cyan" />

    <x-widget-item id="statFemale" label="Jumlah Perempuan" value="0" icon="venus" color="pink" />

    <x-widget-item id="statKK" label="Jumlah Kartu Keluarga" value="0" icon="house" color="green" />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotal', summary.total_penduduk ?? 0);
            updateWidgetCounter('statMale', summary.laki_laki ?? 0);
            updateWidgetCounter('statFemale', summary.perempuan ?? 0);
            updateWidgetCounter('statKK', summary.total_kk ?? 0);
        }

        $('#familCardTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush
