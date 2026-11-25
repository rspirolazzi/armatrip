/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('action-rules-component', require('./components/ActionRules.vue'));
Vue.component('autocomplete-component', require('./components/Autocomplete.vue'));
Vue.component('settings-component', require('./components/Settings.vue'));

const app = new Vue({
    el: '#app',
    created() {
        Echo.channel('orders-events')
            .listen('OrdersStatus', (e) => {
                var notificaciones_count = $(".notificaciones-menu .badge").html();
                parseInt(notificaciones_count);
                notificaciones_count++;
                $(".notificaciones-menu .badge").html(notificaciones_count);
                var notificacion = '<li><a href="/admin/orders">Nuevo Pedido!</a></li>';
                $(".notificaciones-menu .dropdown-menu").append(notificacion);
                var audio = new Audio('/img/notification.mp3') // path to file
                audio.play()
                console.log(window.location.pathname);
                if (window.location.pathname == "/admin/orders") {
                    setTimeout(function () {
                        window.location.reload();
                    }, 1000);
                }
            });
    }
});
