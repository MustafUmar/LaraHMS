/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

axios.defaults.baseURL = 'http://127.0.0.1:8000'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('page-heading', require('./components/segments/PageHeading.vue').default);
Vue.component('page-layout', require('./components/segments/PageLayout.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

//const app = new Vue({
//    el: '#app',
//});

Vue.filter('currency', function (value) {
    if (!value || isNaN(value)) return ''
    value = parseInt(value)
    let x, n = 0
    const re = '\\d(?=(\\d{' + (x || 3) + '})+' + (n > 0 ? '\\.' : '$') + ')'
    return '₦'+value.toFixed(Math.max(0, ~~n)).replace(new RegExp(re, 'g'), '$&,')
    // return '₦'+value.toFixed().replace(/\d(?=(\d{3})+\.)/g, '$&,').toString()
})

Vue.filter('array_join', function (value, key) {
    if (!value) return ''
    return _.map(value, key).join(', ')
})

Vue.filter('mixedCase', function(value) {
    if (!value) return ''
    value = value.toString()
    return value.charAt(0).toUpperCase() + value.slice(1)
})

Vue.filter('truncateZeros', function(value) {
    if (!value) return ''
    return value.replace(/^0+(?!\.|$)/, '')
})

/////////sidebar interact////////////
$(document).on('click','.menu-burger-icon', function() {
    if($('.sidebar-wrapper').hasClass('is-active')) {
        $('.sidebar-wrapper').removeClass('is-active')
        $(this).html('<i class="fas fa-bars fa-lg"></i>')
    } else {
        $('.sidebar-wrapper').addClass('is-active')
        $(this).html('<i class="fas fa-times fa-lg"></i>')
    }

})

$(document).on('click','.show-right-sidebar', function() {
    if($('.right-side-wrapper').hasClass('is-active')) {
        $('.right-side-wrapper').removeClass('is-active')
    } else {
        $('.right-side-wrapper').addClass('is-active')
    }

})

$(document).on('click', '.sidebar-hide-arrow', function() {
    $('.sidebar-wrapper').removeClass('is-active')
})

$(document).on('click', '.side-close-btn .btn', function() {
    $('.right-side-wrapper').removeClass('is-active')
})

/////////hide alert////////////
$('#alert-close').click(function() {
    $('.notification.closable').hide("slow");
})

/////////dropdowns////////////
// $(document).on('click', '.user-drop-trigger', function(e) {
//     $('.useroption-dropdown.dropdown-menu').dropdown('toggle')
// })

/////////handle logout////////////
$(document).on('click', '.logout-link', function(e) {
    e.preventDefault()
    // document.cookie = 'apitoken=; Max-Age=-99999999;';
    // document.cookie = 'tkexp=; Max-Age=-99999999;';
    $('#logout-form').submit()
})

$(document).on('click', '.no-propagate .dropdown-menu', function(e) {
    e.stopPropagation()
})

// $(document).on('submit', '#logout-form', function(e) {
    // e.preventDafault();
    // document.cookie = 'apitoken=; Max-Age=-99999999;';
    // document.cookie = 'tkexp=; Max-Age=-99999999;';
    // console.log('nocookie')
    // $(this).unbind('submit').submit();
    // return false;
// })
