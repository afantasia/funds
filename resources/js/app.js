import "bootstrap/dist/css/bootstrap.min.css";
import 'bootstrap';
import { createApp } from 'vue'
import News from './components/news';
import Charts from './components/charts';
import Trades from './components/trades';
import Navi from './components/navi';
const app = createApp({})

import Vue3EasyDataTable from "vue3-easy-data-table";
import "vue3-easy-data-table/dist/style.css";
app.component('EasyDataTable', Vue3EasyDataTable);
app.component('Navi',Navi);
app.component('News', News);
app.component('Charts',Charts);
app.component('Trades',Trades);
app.mount('#app');
import 'bootstrap/dist/js/bootstrap.js';

