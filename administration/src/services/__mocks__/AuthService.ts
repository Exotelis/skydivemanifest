import { CredentialsModel } from '@/models/CredentialsModel';
import { AxiosError } from 'axios';

export default {
  login (credentials: CredentialsModel): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (credentials.username === 'admin' && credentials.password === 'admin') {
        resolve({
          data: {},
          status: 200,
          statusText: 'Success',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'Something went wrong.' },
        status: 400,
        statusText: 'Bad Request',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

  checkAuth (): boolean {
    return true;
  }
};
