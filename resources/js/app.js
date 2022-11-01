/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue').default;
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import VueCookies from "vue-cookies";
Vue.use(VueCookies);
Vue.use(BootstrapVue);
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin);
// This imports all the layout components such as <b-container>, <b-row>, <b-col>:
import { LayoutPlugin } from 'bootstrap-vue'
Vue.use(LayoutPlugin)

// This imports <b-modal> as well as the v-b-modal directive as a plugin:
import { ModalPlugin } from 'bootstrap-vue'
Vue.use(ModalPlugin)

// This imports <b-card> along with all the <b-card-*> sub-components as a plugin:
import { CardPlugin } from 'bootstrap-vue'
Vue.use(CardPlugin)

// This imports directive v-b-scrollspy as a plugin:
import { VBScrollspyPlugin } from 'bootstrap-vue'
Vue.use(VBScrollspyPlugin)

import Vue from 'vue'
import VTooltip from 'v-tooltip' //추가
Vue.use(VTooltip);

// This imports the dropdown and table plugins
import { DropdownPlugin, TablePlugin } from 'bootstrap-vue'
Vue.use(DropdownPlugin)
Vue.use(TablePlugin)

Vue.component('app-body',require("./components/main.vue").default);



axios.post("/").then((result)=>{
    Vue.$cookies.set("Route",result.data);
});


//axios.defaults.headers.common['X-CSRF-TOKEN']=document.querySelector("meta[name='csrf-token']").content
//실행하긔
const app = new Vue({
    el: '#app',
});