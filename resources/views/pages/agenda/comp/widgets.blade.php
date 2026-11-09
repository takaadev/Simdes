<x-widgets label="Ringkasan Agenda Surat">
    <x-widget-item size="col-12 col-sm-6 col-xl-4" id="statTotalAgenda" label="Total Agenda" icon="book-open" color="blue" />

    <x-widget-item size="col-12 col-sm-6 col-xl-4" id="statIncoming" label="Surat Masuk" icon="inbox" color="green" />

    <x-widget-item size="col-12 col-sm-6 col-xl-4" id="statOutgoing" label="Surat Keluar" icon="paper-plane" color="orange" />
</x-widgets>
@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotalAgenda', summary.total_agenda ?? 0);
            updateWidgetCounter('statIncoming', summary.total_incoming ?? 0);
            updateWidgetCounter('statOutgoing', summary.total_outgoing ?? 0);
        }

        $('#agendaEntriesTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush
