<x-widgets label="Ringkasan Tanah di Desa">
    <x-widget-item id="statSubjekKepemilikan" label="Subjek Kepemilikan" icon="users" color="blue" />
    <x-widget-item id="statJumlahLuas" label="Jumlah Luas Tercatat (M²)" icon="ruler-combined" color="green" />
    <x-widget-item id="statSudahBersertifikat" label="Sudah Bersertifikat (M²)" icon="certificate" color="cyan" />
    <x-widget-item id="statPenggunaanPertanian" label="Penggunaan Pertanian (M²)" icon="seedling" color="orange" />
</x-widgets>

@push('footer')
    <script>
        function updateVillageLandSummary(summary) {
            if (!summary) return;

            updateWidgetCounter(
                'statSubjekKepemilikan',
                summary.subjek_kepemilikan ?? 0
            );

            updateWidgetCounter(
                'statJumlahLuas',
                summary.jumlah_luas_tercatat ?? 0
            );

            updateWidgetCounter(
                'statSudahBersertifikat',
                summary.sudah_bersertifikat ?? 0
            );

            updateWidgetCounter(
                'statPenggunaanPertanian',
                summary.penggunaan_pertanian ?? 0
            );
        }

        $(function() {
            $('#villageLandTable').on('xhr.dt', function(e, settings, json) {
                updateVillageLandSummary(json?.summary);
            });
        });
    </script>
@endpush
