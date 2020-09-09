/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue'

//Componente de validação
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)
//

//Componentes de Terceiros
import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask)

import money from 'v-money'
// register directive v-money and component <money>
Vue.use(money, {
    decimal: ',',
    thousands: '.',
    prefix: 'R$ ',
    suffix: ' #',
    precision: 2,
    masked: false
})

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
Vue.component('v-loading', require('./components/utils/VLoading.vue').default)
Vue.component('v-alert', require('./components/utils/VAlert.vue').default)
Vue.component('v-paginate', require('./components/utils/VPaginate.vue').default)

Vue.component('vue-lista-clientes', require('./components/clientes/ListaClientes.vue').default)
Vue.component('vue-lista-fornecedores', require('./components/fornecedores/ListaFornecedores.vue').default)
Vue.component('vue-lista-produtos', require('./components/produtos/ListaProdutos.vue').default)
Vue.component('vue-lista-vendas', require('./components/vendas/ListaVendas.vue').default)
Vue.component('vue-cadastra-altera-clientes', require('./components/clientes/CadastraOuAlteraClientes.vue').default)
Vue.component('vue-cadastra-altera-fornecedores', require('./components/fornecedores/CadastraOuAlteraFornecedores.vue').default)
Vue.component('vue-cadastra-altera-produtos', require('./components/produtos/CadastraOuAlteraProdutos.vue').default)
Vue.component('vue-cadastra-altera-vendas', require('./components/vendas/CadastraOuAlteraVendas.vue').default)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
});
