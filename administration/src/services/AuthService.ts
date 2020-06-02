import axios, { AxiosResponse } from 'axios';
import jwtDecode from 'jwt-decode';
import { CredentialsModel } from '@/models/CredentialsModel';
import { EventBus } from '@/event-bus';
import { getCookie } from '@/helpers';
import { loadLanguageAsync } from '@/i18n';
import { UserShortModel } from '@/models/UserShortModel';

export default {
  expires: 0,
  refreshToken: null,

  async login (credentials: CredentialsModel): Promise<any> {
    const response: AxiosResponse = await axios.post('/auth', {
      username: credentials.username,
      password: credentials.password
    });

    // In case of success: Store the refresh token only for the session
    this.refreshToken = response.data.refresh_token;

    return response;
  },

  async logout (): Promise<any> {
    const response: AxiosResponse = await axios.post('/auth/logout');

    // In case of success: Clear timeout, local storage and delete cookie
    clearTimeout(this.expires);
    localStorage.removeItem('user');
    document.cookie = 'XSRF-TOKEN= ; expires = Thu, 01 Jan 1970 00:00:00 GMT';

    return response;
  },

  async refresh (token: string): Promise<any> {
    const response = await axios.post('/auth/refresh', { token: token });

    // In case of success: Set new refresh_token
    this.refreshToken = response.data.refresh_token;
  },

  checkAuth (): boolean {
    const cookie: string|undefined = getCookie('XSRF-TOKEN');

    if (cookie === undefined) {
      localStorage.removeItem('user'); // Clear user information
      return false;
    }

    const decryptedPayload: any = jwtDecode(cookie);
    decryptedPayload.exp = decryptedPayload.exp * 1000; // Convert exp date to ms

    // Set user information and permissions
    if (localStorage.getItem('user') === null) {
      let user: UserShortModel = decryptedPayload.user;
      user['permissions'] = decryptedPayload.scopes;
      localStorage.setItem('user', JSON.stringify(user));

      // Set language
      if (user.locale !== null) {
        loadLanguageAsync(user.locale);
        localStorage.setItem('locale', user.locale);
      }
    }

    // Refresh token automatically or show modal dialog one minute before the user gets signed out automatically
    clearTimeout(this.expires);
    this.expires = setTimeout(async (instance: any) => {
      // Try automatically to refresh the access token - Will only work as long as the user hasn't refreshed the app
      try {
        if (instance.refreshToken !== null) {
          await instance.refresh(instance.refreshToken);
          instance.checkAuth();
          return;
        }
      } catch (e) {
      }

      // Emit show modal event
      EventBus.$emit('sign-in-modal');
    }, (decryptedPayload.exp - Date.now()) - 60000, this);

    // Check login status
    return decryptedPayload.exp >= Date.now();
  }
};
