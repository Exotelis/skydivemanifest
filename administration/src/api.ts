import axios, { AxiosError, AxiosResponse } from 'axios';
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

axios.interceptors.response.use((response: AxiosResponse) => {
  return response;
}, (error: AxiosError) => {
  if (error.response && error.response.status === 401) {
    // Force logout on 401 error
    document.cookie = 'XSRF-TOKEN= ; expires = Thu, 01 Jan 1970 00:00:00 GMT';
  }

  return Promise.reject(error);
});
