import axios from 'axios';
import { defaultLanguage } from '@/i18n';
import { getCookie } from '@/helpers';

// Axios default settings
const credentials: boolean = process.env.VUE_APP_API_CREDENTIALS === 'true';
axios.defaults.baseURL = process.env.VUE_APP_API_ENDPOINT || '';
axios.defaults.headers.common['Content-Language'] = defaultLanguage;
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['X-XSRF-TOKEN'] = getCookie('XSRF-TOKEN');
axios.defaults.headers.post['Content-Type'] = 'application/json';
axios.defaults.withCredentials = credentials;
