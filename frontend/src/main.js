import { createApp } from 'vue';
import App from './App.vue';

import router from './router';
import store from "./store";

import 'vuetify/styles';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';

import "bootstrap/dist/css/bootstrap.min.css";
import "bootstrap";
import 'bootstrap-icons/font/bootstrap-icons.css';

import '@mdi/font/css/materialdesignicons.css';

const vuetify = createVuetify({
  components,
  directives,
  icons: {
    iconfont: 'mdi',
  },
});

const app = createApp(App);

app.use(router);
app.use(store);
app.use(vuetify);

app.mount('#app');
