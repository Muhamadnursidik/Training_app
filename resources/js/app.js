
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

require('jquery-form');
require('lang.js');
// require('datatables.net');
// require('datatables.net-bs5');

const Swal = window.Swal = require('sweetalert2/dist/sweetalert2.min');

// Localization
window.__ = function (x, attr) {
    var att = typeof attr != 'undefined' ? attr : {};

    if (Lang.has(x)) {
        return Lang.get(x, att);
    }

    if (Lang.has('_.' + x)) {
        return Lang.get('_.' + x, att);
    }

    return x;
}

/* Helpers */
require('./helpers/ajax');
// require('./helpers/datatable');
require('./helpers/common');

