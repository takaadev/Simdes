<x-widgets label="Ringkasan Data Penduduk Sementara">
    <x-widget-item id="statTotal" label="Total Penduduk Sementara" value="0" icon="users" color="blue" />
    <x-widget-item id="statMale" label="Jumlah Laki-laki" value="0" icon="mars" color="cyan" />
    <x-widget-item id="statFemale" label="Jumlah Perempuan" value="0" icon="venus" color="pink" />
    <x-widget-item id="statStaying" label="Masih Tinggal" value="0" icon="home" color="green" />
    <x-widget-item id="statDeparted" label="Sudah Pergi" value="0" icon="sign-out-alt" color="amber" />
</x-widgets>

@push('footer')
    <script>
        function updateSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotal', summary.total ?? 0);
            updateWidgetCounter('statMale', summary.male ?? 0);
            updateWidgetCounter('statFemale', summary.female ?? 0);
            updateWidgetCounter('statStaying', summary.staying ?? 0);
            updateWidgetCounter('statDeparted', summary.departed ?? 0);
        }

        $('#temporaryResidentTable').on('xhr.dt', function(e, settings, json) {
            updateSummary(json?.summary);
        });
    </script>
@endpush