import './bootstrap';
import './components/ajax';
import './components/alerts';
import './components/select2';

import { initSelect2 } from './components/select2';
import { initDataTables } from './config/datatables';

$(function () {
    initSelect2();
    initDataTables();
});