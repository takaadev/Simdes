<?php

if (! function_exists('format_date_input')) {
    /**
     * Format tanggal untuk HTML input type="date".
     */
    function format_date_input($date): ?string
    {
        if (empty($date)) {
            return null;
        }

        return \Carbon\Carbon::parse($date)->format('Y-m-d');
    }
}

if (! function_exists('format_datetime_input')) {
    /**
     * Format tanggal dan waktu untuk HTML input type="datetime-local".
     */
    function format_datetime_input($datetime): ?string
    {
        if (empty($datetime)) {
            return null;
        }

        return \Carbon\Carbon::parse($datetime)->format('Y-m-d\TH:i');
    }
}

if (! function_exists('format_date_display')) {
    /**
     * Format tanggal untuk tampilan aplikasi.
     */
    function format_date_display($date, string $format = 'd-m-Y'): ?string
    {
        if (empty($date)) {
            return null;
        }

        return \Carbon\Carbon::parse($date)->format($format);
    }
}