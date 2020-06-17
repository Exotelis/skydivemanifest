import { AxiosError } from 'axios';
import { CredentialsModel } from '@/models/CredentialsModel';
import { RegisterModel } from '@/models/RegisterModel';

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

  register (data: RegisterModel): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (data.email === 'exotelis@mailbox.org') {
        resolve({
          data: {
            'message': 'The user has been created successfully.',
            'data': {
              'default_invoice': null,
              'default_shipping': null,
              'deleted_at': null,
              'email_verified_at': null,
              'failed_logins': 0,
              'gender': 'm',
              'is_active': true,
              'last_logged_in': null,
              'locale': 'de',
              'lock_expires': null,
              'middlename': null,
              'mobile': null,
              'password_change': false,
              'phone': null,
              'username': 'johndoe',
              'timezone': 'UTC',
              'dob': '2000-01-01',
              'email': 'exotelis@mailbox.org',
              'firstname': 'John',
              'lastname': 'Doe',
              'updated_at': '2020-03-15T12:50:28.000000Z',
              'created_at': '2020-03-15T12:50:28.000000Z',
              'id': 10,
              'role': {
                'id': 2,
                'deletable': false,
                'editable': false,
                'name': 'User',
                'created_at': '2020-03-14T13:45:24.000000Z',
                'updated_at': '2020-03-14T13:45:24.000000Z'
              }
            }
          },
          status: 201,
          statusText: 'Created',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'The registration failed.' },
        status: 500,
        statusText: 'Internal Server Error',
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
