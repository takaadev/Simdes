<x-widgets label="Ringkasan Inventaris dan Kekayaan Desa">
    <x-widget-item id="statTotalTypes" label="Total Jenis Barang/Bangunan" icon="boxes-stacked" color="blue" />
    <x-widget-item id="statGood" label="Akhir Tahun (Baik)" icon="circle-check" color="green" />
    <x-widget-item id="statDamaged" label="Akhir Tahun (Rusak)" icon="triangle-exclamation" color="orange" />
    <x-widget-item id="statDeleted" label="Total Unit Dihapuskan" icon="trash-can" color="red" />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotalTypes', summary.total_types ?? 0);
            updateWidgetCounter('statGood', summary.good ?? 0);
            updateWidgetCounter('statDamaged', summary.damaged ?? 0);
            updateWidgetCounter('statDeleted', summary.deleted ?? 0);
        }

        $('#villageAssetsTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush
