import Vue from 'vue';
import App from '@/App';
import router from '@/router/router';
import '@/registerServiceWorker';
import { defaultLanguage, i18n, loadLanguageAsync } from '@/i18n';

// Import 3rd party libraries
import jQuery from 'jquery';
import 'popper.js';
import 'bootstrap';

// Make jQuery accessible
window.$ = window.jQuery = jQuery;

Vue.config.productionTip = false;

// Wait for Promise to be resolved (language file to be loaded)
Promise.all([loadLanguageAsync(defaultLanguage)]).finally(() => {
  new Vue({
    router,
    i18n,
    render: h => h(App)
  }).$mount('#app');
});
