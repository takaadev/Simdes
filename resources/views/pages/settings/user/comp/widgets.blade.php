<x-widgets label="Ringkasan Akun Pengguna">
    <x-widget-item id="statTotalUsers" label="Total Pengguna" value="0" icon="users" color="blue" size="col-md-6" />
    <x-widget-item id="statOnlineUsers" label="Sedang Login" value="0" icon="user-check" color="green" size="col-md-6" />
</x-widgets>

@push('footer')
    <script>
        function updateUserSummary(summary) {
            if (!summary) return;

            updateWidgetCounter('statTotalUsers', summary.total_user ?? 0);
            updateWidgetCounter('statOnlineUsers', summary.total_online ?? 0);
        }

        $(function() {
            $('#userTable').on('xhr.dt', function(e, settings, json) {
                updateUserSummary(json?.summary);
            });
        });
    </script>
@endpush
