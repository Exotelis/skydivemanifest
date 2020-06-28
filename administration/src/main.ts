import Vue from 'vue';
import App from '@/App.vue';
import router from '@/router/router';
import '@/registerServiceWorker';
import { defaultLanguage, i18n, loadLanguageAsync } from '@/i18n';

// Import 3rd party libraries
import 'jquery';
import 'popper.js';
import 'bootstrap';

// Require api config
require('@/api.ts');

Vue.config.productionTip = false;

// Wait for Promise to be resolved (language file to be loaded)
Promise.all([loadLanguageAsync(defaultLanguage)]).finally(() => {
  new Vue({
    router,
    i18n,
    render: h => h(App)
  }).$mount('#app');
});
