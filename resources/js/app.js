require('./bootstrap');

window.Vue = require('vue');

var VueResource = require('vue-resource');
Vue.use(VueResource);

Vue.http.headers.common['X-CSRF-TOKEN'] = document.head.querySelector('meta[name="csrf-token"]').content;

import ProductsComponent from './components/ProductsComponent'

const app = new Vue({
    el: '#app',
    render: h => h(ProductsComponent)
});
