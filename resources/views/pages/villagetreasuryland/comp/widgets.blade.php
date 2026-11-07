<x-widgets label="Ringkasan Tanah Kas Desa">
    <x-widget-item
        id="statTotalRecords"
        label="Total Bidang Tanah Kas"
        icon="map"
        color="blue"
    />

    <x-widget-item
        id="statTotalArea"
        label="Total Luas Tercatat (m²)"
        icon="ruler-combined"
        color="green"
    />

    <x-widget-item
        id="statTotalMarked"
        label="Patok Tanda Batas Ada"
        icon="map-pin"
        color="cyan"
    />

    <x-widget-item
        id="statTotalNamed"
        label="Papan Nama Terpasang"
        icon="sign-hanging"
        color="orange"
    />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter(
                'statTotalRecords',
                summary.total_records ?? 0
            );

            updateWidgetCounter(
                'statTotalArea',
                summary.total_area ?? 0
            );

            updateWidgetCounter(
                'statTotalMarked',
                summary.total_marked ?? 0
            );

            updateWidgetCounter(
                'statTotalNamed',
                summary.total_named ?? 0
            );
        }

        $('#villageTreasuryLandTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush