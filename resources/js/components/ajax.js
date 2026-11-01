import { $ } from '../bootstrap';

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content'),

        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
    },
});

export default $;