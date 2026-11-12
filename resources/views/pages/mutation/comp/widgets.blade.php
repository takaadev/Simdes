<x-widgets label="Ringkasan Mutasi Penduduk">
    <x-widget-item id="statTotal" label="Total Mutasi" value="0" icon="users" color="blue" />

    <x-widget-item id="statAddition" label="Penambahan" value="0" icon="user-plus" color="green" />

    <x-widget-item id="statReduction" label="Pengurangan" value="0" icon="user-minus" color="orange" />

    <x-widget-item id="statDeath" label="Kematian" value="0" icon="heart-crack" color="red" />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotal', summary.total ?? 0);
            updateWidgetCounter('statAddition', summary.addition ?? 0);
            updateWidgetCounter('statReduction', summary.reduction ?? 0);
            updateWidgetCounter('statDeath', summary.death ?? 0);
        }

        $('#residentMutationTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush
