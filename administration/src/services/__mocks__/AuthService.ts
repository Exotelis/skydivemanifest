import axios, { AxiosError } from 'axios';
import ChangePasswordModel from '@/models/ChangePasswordModel';
import CredentialsModel from '@/models/CredentialsModel';
import RegisterModel from '@/models/RegisterModel';
import ResetPasswordModel from '@/models/ResetPasswordModel';
import { getCookie } from '@/helpers';
import jwtDecode from 'jwt-decode';
import UserShortModel from '@/models/UserShortModel';

export default {
  acceptTos (tos: boolean): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (tos) {
        resolve({
          data: { message: 'The Terms of Service have been accepted.' },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'Bad Request.' },
        status: 400,
        statusText: 'Bad Request',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

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

  forgotPassword (username: string): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (username === 'exotelis@mailbox.org') {
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
      if ((credentials.username === 'admin' || credentials.username === 'exotelis@mailbox.org') &&
        credentials.password === 'admin') {
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

  refresh (token: string): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (token !== '0123456789') {
        resolve({
          data: {
            'token_type': 'Bearer',
            'expires_in': 7200,
            'access_token': 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiZmU3NmEwMjE3NjJhMGRlMTE2MDIzZDczMjAyYmNkYzQzZDE2ZmU5YTIwODM5MWU0N2JmZjYwYmZmMDNmMGUxNGZmYWFlMDZiZGE0ZGYzN2YiLCJpYXQiOjE1ODYwODc4MzksIm5iZiI6MTU4NjA4NzgzOSwiZXhwIjoxNTg2MDk1MDM5LCJzdWIiOiIxIiwic2NvcGVzIjpbImNvdW50cmllczpkZWxldGUiLCJjb3VudHJpZXM6cmVhZCIsImNvdW50cmllczp3cml0ZSIsImN1cnJlbmNpZXM6ZGVsZXRlIiwiY3VycmVuY2llczpyZWFkIiwiY3VycmVuY2llczp3cml0ZSIsInBlcm1pc3Npb25zOnJlYWQiLCJwZXJzb25hbCIsInJlZ2lvbnM6ZGVsZXRlIiwicmVnaW9uczpyZWFkIiwicmVnaW9uczp3cml0ZSIsInJvbGVzOmRlbGV0ZSIsInJvbGVzOnJlYWQiLCJyb2xlczp3cml0ZSIsInVzZXJzOmRlbGV0ZSIsInVzZXJzOnJlYWQiLCJ1c2Vyczp3cml0ZSJdfQ.xKvx1NAwz0A7gKx8VB_NRnO7jjHr9vS6HqNlPHbMJ89KNOs2WsJ_766pe7Kq7MnJDTgEU4zxSh86c0SwzNksUAKwtEnrbWYWQS1xIxP9HBLXqDf5Cj8UzS6X-W7LhVlSVMJ2fDePp-bDLIwRZiYDkqr1otEZe8Nl7hOcebiOycyzibHb0T6ca6MkIStFdlbHwHwFPiaUEqMq3Q5DcV6-y6cfi-1LgILzrPsD8_PwTt_4ovRMdP6lX1anKBmbu6rhtDbyVYBUiECJWIhVDHMynG9luG4xFzXVt6wdpRXGaCB2NVlZMELkpqJAWD-7ZPNQUt2pSkny2Dikj-dpEI0xkYnK_CF9YENMUBCZa_UIpWfxbX-h2dLOC8-hSJUVL4WtKyxw-sCPioH0ox5OvpUkv9ux5PEeAVybeYCvjQLTLdD77BRvy8_zgHzTCbAQ9ccslpLRKv_IlDT-8QfLqDm3pfeZ3ZA7Jtzxz6MVeKb3Y4bLwgNtBYJToWzMgdOePJ42zcPGDO2kBjKEhvayt4-7_YXEkPCfa1-qm4s4C5Q-PoJRaahrYz_0CQsM5ydV0yA_l19AKFgVfV3spt_jRpnoenDfR4oWqka_KECm97of-YVbB8EFXs-PkG76BXlRFlztzYjgzeECM6iqz23T0rYe-ZjCfptfT42Ekx6tAdesuV8',
            'refresh_token': 'def50200a57cab5aab2e4e34e414a737cba8fa994e2cfdd948f423816882c5ff89d1d5cc157e7c3c98dc17af7157dfc522b8123dc0900dced81949e6445d072b90bac6160c155f740e1838ec832be4fb3a352dc72af9ce0314e6607600efe34bdb19afe36d5fbcdc5b07dbb029eed3c35bd2a4d307b725c849683c6dd121534e7c957590b5250629e589cdd44e218503d08ef451222825c1782a8a367405bf5696bf646a5bca3cb1ddbf40aea6562352241ca3e3095f3085110bc46a23d0ae449107aeab9aaab47a83bcfc7e94b06829980dc83e35a844e8850b1c09fbb740f8ca0151618ff1020a0b996dbeb82e7dc5fc1d843cbbe81463f73059ae3c62d71dbd0cdd627c57da4d59cbfedaa321c5b2a23dd81e5450b9c7707e27e22f48bf1c373eb6dc376eaaa86a9dc0afcb5c2469aa66dbb9c666c10f82ac22d7e6f564e75cd58c320abf5896cdeb3f7a4e2f827428a1abd44b6320c11b8f7b65b122cdf381437504dbac5216152129706476bc943dfca208c9da11c88cd34b823f5cf94a8e8fbe035394cbbe4c9043facbe4b4c757fc6451653f9508a2d09be9f5f87b6851cbc9eee82c4545e5c173e7fc7d9425534f434f26805eae4a36e69579ff681bcf146bb47013e7c91358be1dd0907f11ef88b94d51359a0930ef31861a8cb4921b28bcbb8631366394955fbb54416ccd8a04a43a6e6f310b1b7e73809fa67b3a51835e54b373378669169bc72d4edf681c0ac70d947cf036172ff4f91a410b598fdcf4f93e97328f667b7c24ca1afb1519ff8eda9d89fcb68330196b6715388ec5f94737ddc471154d5ca7f3a4191dd1cbed7d920674b0e8b8afff6cd17dea5dd0a02bdff3057a9ecaa62137e5a3f2c9ba'
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'Could not get an access token.' },
        status: 401,
        statusText: 'Unauthorized',
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

  mustAcceptTos: jest.fn()
    .mockReturnValue(false),

  passwordChangeRequired: jest.fn()
    .mockReturnValue(false)
};
