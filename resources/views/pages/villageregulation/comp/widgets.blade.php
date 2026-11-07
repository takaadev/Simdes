<x-widgets label="Ringkasan Peraturan Desa">
    <x-widget-item id="statStatusDraft" label="Draft" icon="file-pen" color="gray" />
    <x-widget-item id="statStatusActive" label="Berlaku" icon="circle-check" color="green" />
    <x-widget-item id="statStatusRevoked" label="Dicabut" icon="ban" color="orange" />
    <x-widget-item id="statStatusAmended" label="Diubah" icon="file-circle-check" color="cyan" />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;
            updateWidgetCounter('statStatusDraft', summary.draft ?? 0);
            updateWidgetCounter('statStatusActive', summary.active ?? 0);
            updateWidgetCounter('statStatusRevoked', summary.revoked ?? 0);
            updateWidgetCounter('statStatusAmended', summary.amended ?? 0);
        }

        $('#villageRegulationsTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush
