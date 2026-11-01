import { $ } from './bootstrap';

import './components/ajax';
import './components/alerts';
import './components/select2';
import './components/sidebar';
import './components/main'

import { initSelect2 } from './components/select2';
import { initDataTables } from './config/datatables';

$(function () {
    initSelect2();
    initDataTables();
});