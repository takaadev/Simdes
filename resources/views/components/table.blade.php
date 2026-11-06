@props([
    'id' => 'simdesDataTable',
])
<section class="simdes-table-container">
    <table class="simdes-ledger-table table table-bordered table-hover align-middle mb-0" id="{{ $id }}">
        <thead>
            {{ $slot }}
        </thead>
    </table>
</section>
