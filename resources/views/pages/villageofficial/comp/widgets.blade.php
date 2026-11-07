<x-widgets label="Ringkasan Aparat Pemerintah Desa">
    <x-widget-item id="statTotal" label="Total Aparat Tercatat" value="0" icon="users" color="blue" />
    <x-widget-item id="statActive" label="Aparat Aktif Menjabat" value="0" icon="user-check" color="green" />
    <x-widget-item id="statGender" label="Komposisi L / P" value="0 / 0" icon="venus-mars" color="cyan" />
    <x-widget-item id="statInactive" label="Purna Tugas / Berhenti" value="0" icon="user-minus" color="orange" />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotal', summary.total ?? 0);
            updateWidgetCounter('statActive', summary.active ?? 0);
            updateWidgetCounter('statInactive', summary.inactive ?? 0);
            updateWidgetPair(
                'statGender',
                summary.male ?? 0,
                summary.female ?? 0
            );
        }

        $('#villageOfficialTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush
