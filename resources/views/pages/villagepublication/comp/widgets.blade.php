<x-widgets label="Ringkasan Publikasi Peraturan Desa">
    <x-widget-item size="col-12 col-sm-6 col-xl-4" id="statTotalRecords" label="Total Peraturan Tercatat" icon="book-open" color="blue" />
    <x-widget-item size="col-12 col-sm-6 col-xl-4" id="statStatusActive" label="Peraturan Berlaku" icon="circle-check" color="green" />
    <x-widget-item size="col-12 col-sm-6 col-xl-4" id="statStatusRevoked" label="Peraturan Dicabut" icon="ban" color="danger" />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotalRecords', summary.total ?? 0);
            updateWidgetCounter('statStatusActive', summary.active ?? 0);
            updateWidgetCounter('statStatusRevoked', summary.revoked ?? 0);
        }

        $('#villagePublicationTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush