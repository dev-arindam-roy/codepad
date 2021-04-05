/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
window.Vue = require('vue').default;

//Vuelidate
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

//Toastr
import CxltToastr from 'cxlt-vue2-toastr'
import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'

var toastrConfigs = {
    position: 'top right',
    showDuration: 1000,
    timeOut: 5000,
    closeButton: true,
    showMethod: 'fadeIn',
    hideMethod: 'fadeOut',
    //progressBar: true
}
Vue.use(CxltToastr, toastrConfigs)

//Sweet Alert2
import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';
const options = {
    confirmButtonColor: '#000',
    cancelButtonColor: '#000',
};
Vue.use(VueSweetalert2, options);

//Pagination
Vue.prototype.$defaultPagination = 10;
Vue.component('pagination', require('laravel-vue-pagination'));

//Moment
Vue.use(require('vue-moment'));

// import routes
import webRoutes from './route';

// import components
Vue.component('page-loader', require('./components/pageLoadingComponent.vue').default);
import appIndex from './components/appIndexComponent'

const app = new Vue({
    data() {
        return {
            isPageLoadingActive: false
        }
    },
    components: {
        appIndex
    },
    methods: {

    },
    mounted() {

    },
    el: '#app',
    router: webRoutes
});
