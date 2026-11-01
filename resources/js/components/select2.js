import $ from 'jquery';

export async function initSelect2(container = document) {
    // Pastikan plugin Select2 sudah dimuat
    await import('select2');

    $(container).find('.select2').addBack('.select2').each(function () {
        if ($(this).hasClass('select2-hidden-accessible')) {
            return;
        }

        $(this).select2({
            width: '100%',
            placeholder: 'Pilih data',
            allowClear: true
        });
    });
}