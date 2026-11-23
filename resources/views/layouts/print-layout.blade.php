@props([
    'title' => 'Laporan Administrasi Desa',
    'subtitle' => 'PEMERINTAH DESA TIARO',
    'paper' => 'A4 landscape',
    'fontSize' => '8pt',
    'filterSummary' => null,
    'totalRecords' => null,
])

@php
    $isModal = request()->boolean('modal');
    $allowedPapers = ['A4 landscape', '330mm 215mm', 'A4 portrait'];
    $activePaper = in_array(request('paper'), $allowedPapers, true) ? request('paper') : $paper;
@endphp

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> {{ config('app.name') }} |{{ $title }}</title>
    <style>
        @page {
            size: {{ $activePaper }};
            margin: 10mm 10mm 12mm 10mm;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 14px;
            font-family: Arial, Helvetica, sans-serif;
            font-size: {{ $fontSize }};
            color: #111827;
            background: #f3f4f6;
        }

        .print-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
            padding: 10px 14px;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
        }

        .print-sheet {
            background: #ffffff;
            padding: 16px 18px;
            border-radius: 6px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
        }

        .print-header {
            text-align: center;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 2px solid #111827;
        }

        .print-header h1 {
            margin: 0;
            font-size: 12.5pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .print-header .subtitle {
            margin-top: 2px;
            font-size: 9.5pt;
            font-weight: 700;
            text-transform: uppercase;
        }

        .print-header .meta {
            margin-top: 4px;
            font-size: 7.5pt;
            color: #4b5563;
        }

        table.print-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.print-table thead {
            display: table-header-group;
        }

        table.print-table tfoot {
            display: table-footer-group;
        }

        table.print-table tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        table.print-table th,
        table.print-table td {
            border: 1px solid #1f2937;
            padding: 3.5px 4px;
            vertical-align: top;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        table.print-table th {
            background: #f3f4f6;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
        }

        .text-center {
            text-align: center !important;
        }

        .text-end {
            text-align: right !important;
        }

        .text-nowrap {
            white-space: nowrap !important;
        }

        .fw-bold {
            font-weight: 700 !important;
        }

        .print-signature {
            display: flex;
            justify-content: space-between;
            margin-top: 22px;
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .signature-box {
            width: 38%;
            text-align: center;
            font-size: 8.5pt;
        }

        .signature-space {
            height: 54px;
        }

        .signature-name {
            font-weight: 700;
            text-decoration: underline;
        }

        @media print {
            body {
                padding: 0;
                background: #ffffff;
            }

            .no-print {
                display: none !important;
            }

            .print-sheet {
                padding: 0;
                border-radius: 0;
                box-shadow: none;
            }
        }
    </style>
    @stack('print-styles')
</head>

<body>
    @if (!$isModal)
        <div class="print-toolbar no-print">
            <strong>Pratinjau Cetak: {{ $title }}</strong>
            <div style="display:flex; gap:8px;">
                <button type="button" onclick="window.print()">Cetak / Simpan PDF</button>
                <button type="button" onclick="window.close()">Tutup</button>
            </div>
        </div>
    @endif

    <div class="print-sheet">
        <header class="print-header">
            <h1>{{ $title }}</h1>
            <div class="subtitle">{{ $subtitle }}</div>
            <div class="meta">
                Dicetak pada {{ now()->locale('id')->translatedFormat('d F Y, H:i') }} WIB
                @if (!is_null($totalRecords))
                    &bull; Total Data: {{ $totalRecords }} baris
                @endif
                @if ($filterSummary)
                    &bull; {{ $filterSummary }}
                @endif
            </div>
        </header>

        <main>{{ $slot }}</main>

        <footer class="print-signature">
            <div class="signature-box">
                <div>Mengetahui,</div>
                <div>Kepala Desa Tiaro</div>
                <div class="signature-space"></div>
                <div class="signature-name">( ________________________ )</div>
            </div>

            <div class="signature-box">
                <div>Desa Tiaro, {{ now()->locale('id')->translatedFormat('d F Y') }}</div>
                <div>Sekretaris Desa</div>
                <div class="signature-space"></div>
                <div class="signature-name">( ________________________ )</div>
            </div>
        </footer>
    </div>
</body>

</html>
