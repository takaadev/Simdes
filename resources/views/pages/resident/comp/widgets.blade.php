<x-widgets label="Ringkasan Data Penduduk">
    <x-widget-item id="statTotal" label="Total Penduduk" value="0" icon="users" color="blue" />
    <x-widget-item id="statGender" label="Laki-laki / Perempuan" value="0 / 0" icon="venus-mars" color="cyan" />
    <x-widget-item id="statCitizenship" label="WNI / WNA" value="0 / 0" icon="globe" color="green" />
    <x-widget-item id="statStatusResident" label="Aktif / Tidak Aktif" value="0 / 0" icon="user-check"
        color="purple" />
</x-widgets>
@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotal', summary.total ?? 0);
            updateWidgetPair(
                'statCitizenship',
                summary.wni ?? 0,
                summary.wna ?? 0
            );

            updateWidgetPair(
                'statGender',
                summary.male ?? 0,
                summary.female ?? 0
            );

            updateWidgetPair(
                'statStatusResident',
                summary.active ?? 0,
                summary.inactive ?? 0
            );
        }

        $('#residentTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush
