import "bootstrap/dist/css/bootstrap.min.css";
import 'bootstrap';
import { createApp } from 'vue'
import News from './components/news';
import Charts from './components/charts';

import axios from 'axios';
import bootstrap from 'bootstrap';


const app = createApp({})

app.component('News', News);
app.component('Charts',Charts);

app.mount('#app');
//import 'bootstrap/dist/js/bootstrap.js';

