import $ from 'jquery';

// jQuery
window.$ = window.jQuery = $;

// Bootstrap 5
const bootstrap = await import('bootstrap');
window.bootstrap = bootstrap;

export { $, bootstrap };