window._ = require('lodash');
window.Popper = require('popper.js').default;
window.Vue = require('vue');

Vue.component('pagination', require('laravel-vue-pagination'));

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.events = new Vue();

window.flash = function(message, level = 'success') {
    window.events.$emit('flash', { message, level });
};
