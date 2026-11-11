<x-widgets label="Ringkasan Data Penduduk">
    <x-widget-item id="statTotal" label="Total Penduduk" value="0" icon="users" color="blue" />
    <x-widget-item id="statMale" label="Penduduk Laki-laki" value="0" icon="mars" color="cyan" />
    <x-widget-item id="statFemale" label="Penduduk Perempuan" value="0" icon="venus" color="pink" />
    <x-widget-item id="statWni" label="Warga Negara Indonesia" value="0" icon="flag" color="green" />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotal', summary.total ?? 0);
            updateWidgetCounter('statMale', summary.male ?? 0);
            updateWidgetCounter('statFemale', summary.female ?? 0);
            updateWidgetCounter('statWni', summary.wni ?? 0);
        }

        $('#residentTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush