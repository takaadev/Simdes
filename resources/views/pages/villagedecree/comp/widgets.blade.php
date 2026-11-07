<x-widgets label="Ringkasan Keputusan Kepala Desa">
    <x-widget-item id="statTotal" label="Total Keputusan Tercatat" icon="file-lines" color="blue"
        size="col-12 col-sm-6 col-xl-4" />
    <x-widget-item id="statReported" label="Sudah Dilaporkan" icon="circle-check" color="green"
        size="col-12 col-sm-6 col-xl-4" />
    <x-widget-item id="statUnreported" label="Belum Dilaporkan" icon="clock" color="orange"
        size="col-12 col-sm-6 col-xl-4" />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotal', summary.total ?? 0);
            updateWidgetCounter('statReported', summary.reported ?? 0);
            updateWidgetCounter('statUnreported', summary.unreported ?? 0);
        }

        $('#villageDecreesTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush
