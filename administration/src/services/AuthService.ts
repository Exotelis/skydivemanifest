import axios, { AxiosResponse } from 'axios';
import jwtDecode from 'jwt-decode';
import { EventBus } from '@/event-bus';
import { getCookie } from '@/helpers';
import { loadLanguageAsync } from '@/i18n';
import ChangePasswordModel from '@/models/ChangePasswordModel';
import CredentialsModel from '@/models/CredentialsModel';
import RegisterModel from '@/models/RegisterModel';
import ResetPasswordModel from '@/models/ResetPasswordModel';
import UserShortModel from '@/models/UserShortModel';

export default {
  expires: 0,
  refreshToken: null,

  async acceptTos (tos: boolean): Promise<any> {
    return axios.post('/auth/tos', { tos: tos });
  },

  async changePassword (passwords: ChangePasswordModel): Promise<any> {
    return axios.post('/auth/password/change', passwords);
  },

  async confirmEmail (token: string): Promise<any> {
    return axios.post('/auth/email/confirm', { token: token });
  },

  async forgotPassword (username: string): Promise<any> {
    return axios.post('/auth/password/forgot', { username: username });
  },

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
    document.cookie = 'XSRF-TOKEN= ; expires = Thu, 01 Jan 1970 00:00:00 GMT';

    return response;
  },

  async refresh (token: string): Promise<any> {
    const response = await axios.post('/auth/refresh', { token: token });

    // In case of success: Set new refresh_token
    this.refreshToken = response.data.refresh_token;
  },

  async register (data: RegisterModel): Promise<any> {
    return axios.post('/auth/register', data);
  },

  async resetPassword (data: ResetPasswordModel): Promise<any> {
    return axios.post('/auth/password/reset', data);
  },

  checkAuth (): boolean {
    const cookie: string|undefined = getCookie('XSRF-TOKEN');

    if (cookie === undefined) {
      return false;
    }

    const decryptedPayload: any = jwtDecode(cookie);
    const user = decryptedPayload.user;
    decryptedPayload.exp = decryptedPayload.exp * 1000; // Convert exp date to ms

    // Set language
    if (user.locale !== null) {
      loadLanguageAsync(user.locale);
      localStorage.setItem('locale', user.locale);
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

        // Emit show modal event
        EventBus.$emit('sign-in-modal');
      } catch (e) {
        if (decryptedPayload.exp >= Date.now() && getCookie('XSRF-TOKEN') !== undefined) {
          // Emit show modal event
          EventBus.$emit('sign-in-modal');
        }
      }
    }, (decryptedPayload.exp - Date.now()) - 60000, this);

    // Check login status
    return decryptedPayload.exp >= Date.now();
  },

  mustAcceptTos (): boolean {
    const cookie: string|undefined = getCookie('XSRF-TOKEN');

    if (cookie === undefined) {
      return false;
    }

    const decryptedPayload: any = jwtDecode(cookie);
    let user: UserShortModel = decryptedPayload.user;
    return !user.tos;
  },

  passwordChangeRequired (): boolean {
    const cookie: string|undefined = getCookie('XSRF-TOKEN');

    if (cookie === undefined) {
      return false;
    }

    const decryptedPayload: any = jwtDecode(cookie);
    let user: UserShortModel = decryptedPayload.user;
    return user.password_change;
  }
};
