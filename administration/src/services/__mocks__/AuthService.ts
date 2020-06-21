import { AxiosError } from 'axios';
import ChangePasswordModel from '@/models/ChangePasswordModel';
import CredentialsModel from '@/models/CredentialsModel';
import RegisterModel from '@/models/RegisterModel';
import ResetPasswordModel from '@/models/ResetPasswordModel';

export default {
  changePassword (passwords: ChangePasswordModel): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (passwords.password === 'secret') {
        resolve({
          data: { message: 'Your password has been changed successfully.' },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'This action is unauthorized.' },
        status: 403,
        statusText: 'Forbidden',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

  confirmEmail (token: String): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (token === 'ace97ba108eb058d18384e70cba8f13a91332165ea9b271b4f2d0003ae0f0337') {
        resolve({
          data: { message: 'Email address has been verified successfully.' },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'This email change token is invalid.' },
        status: 400,
        statusText: 'Bad Request',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

  forgotPassword (email: string): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (email === 'exotelis@mailbox.org') {
        resolve({
          data: { message: 'We have emailed your password reset link!' },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'Your password cannot be reset.' },
        status: 400,
        statusText: 'Bad Request',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

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

  logout (): Promise<any> {
    return new Promise<any>((resolve) => {
      resolve({
        data: {},
        status: 200,
        statusText: 'Success',
        headers: {},
        config: {}
      });
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

  resetPassword (data: ResetPasswordModel): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (data.email === 'exotelis@mailbox.org') {
        resolve({
          data: { message: 'Your password has been reset!' },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'The password reset token is invalid or expired.' },
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
  },

  passwordChangeRequired: jest.fn()
    .mockReturnValue(true)
    .mockReturnValueOnce(true)
    .mockReturnValueOnce(false)
};
