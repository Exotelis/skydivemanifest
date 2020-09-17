import { AxiosError } from 'axios';
import RoleModel from '@/models/RoleModel';

export default {
  add (data: RoleModel): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (data.name === 'Moderator') {
        resolve({
          data: {
            'message': 'The user role has been created successfully.',
            'data': {
              'deletable': true,
              'editable': true,
              'color': data.color,
              'name': data.name,
              'updated_at': '2020-03-15T20:00:00.000000Z',
              'created_at': '2020-03-15T20:00:00.000000Z',
              'id': 3
            }
          },
          status: 201,
          statusText: 'CreatedOK',
          headers: {},
          config: {}
        });
      }

      if (data.name === 'Administrator') {
        const e = new Error('Something went wrong') as AxiosError;
        e.response = {
          data: { message: 'The given data was invalid.', errors: { 'name': ['The name has already been taken.'] } },
          status: 422,
          statusText: 'Unprocessable Entity',
          headers: {},
          config: {}
        };
        reject(e);
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'The request failed.' },
        status: 400,
        statusText: 'Bad Request',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

  all (params?: any): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (params && params.limit !== 999) {
        resolve({
          data: {
            current_page: 1,
            data: [
              { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T12:00:00.000000Z', 'updated_at': '2020-07-05T12:00:00.000000Z' },
              { 'id': 2, 'color': '#6c757d', 'deletable': true, 'editable': true, 'name': 'User', 'created_at': '2020-07-05T12:00:00.000000Z', 'updated_at': '2020-07-05T12:00:00.000000Z' },
              { 'id': 3, 'color': '#ed7c3b', 'deletable': true, 'editable': true, 'name': 'Moderator', 'created_at': '2020-07-05T12:00:00.000000Z', 'updated_at': '2020-07-05T12:00:00.000000Z' }
            ],
            from: 1,
            last_page: 1,
            per_page: 10,
            to: 10,
            total: 3
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'The request failed.' },
        status: 400,
        statusText: 'Bad Request',
        headers: {},
        config: {}
      };
      reject(e);
    });
  },

  bulkDelete (ids: Array<number>): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (ids[0] < 999) {
        resolve({
          data: {
            count: ids.length,
            message: ids.length === 1 ? 'The user role has been deleted.' : ids.length + ' user roles have been deleted.'
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;

      if (ids[0] === 999) {
        e.response = {
          data: {
            message: 'User roles could not be deleted.',
            errors: {
              'ids.0': ['At least one user is still assigned to the user role with id 1, remove all users from this role to proceed.'],
              'ids.1': ['The user role with the id 2 is protected.']
            }
          },
          status: 422,
          statusText: 'Unprocessable Entity',
          headers: {},
          config: {}
        };
      } else {
        e.response = {
          data: {
            message: 'Invalid scope(s) provided.'
          },
          status: 403,
          statusText: 'Forbidden',
          headers: {},
          config: {}
        };
      }

      reject(e);
    });
  },

  delete (id: number): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (id === 1) {
        resolve({
          data: {
            'message': 'The user role has been deleted successfully.'
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;

      if (id === 2) {
        e.response = {
          data: { 'message': 'The requested resource was not found.' },
          status: 404,
          statusText: 'Not Found',
          headers: {},
          config: {}
        };
      } else {
        e.response = {
          data: { message: 'The request failed.' },
          status: 400,
          statusText: 'Bad Request',
          headers: {},
          config: {}
        };
      }

      reject(e);
    });
  },

  get (id: number): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (id === 1) {
        resolve({
          data: {
            'id': 1,
            'color': '#1d2530',
            'deletable': false,
            'editable': false,
            'name': 'Administrator',
            'created_at': '2020-03-15T20:00:00.000000Z',
            'updated_at': '2020-03-15T20:00:00.000000Z',
            'permissions': [
              {
                'slug': 'personal',
                'is_default': true,
                'name': 'Access to personal information',
                'created_at': '2020-03-15T20:00:00.000000Z',
                'updated_at': '2020-03-15T20:00:00.000000Z',
                'pivot': {
                  'role_id': 1,
                  'permission_slug': 'personal'
                }
              }
            ]
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;

      if (id === 2) {
        e.response = {
          data: { 'message': 'The requested resource was not found.' },
          status: 404,
          statusText: 'Not Found',
          headers: {},
          config: {}
        };
      } else {
        e.response = {
          data: {
            message: 'At least one user is still assigned to this user role, remove all users from this role to proceed.'
          },
          status: 400,
          statusText: 'Bad Request',
          headers: {},
          config: {}
        };
      }

      reject(e);
    });
  },

  names: jest.fn()
    .mockImplementationOnce(() => Promise.resolve({ data: ['Administrator', 'User', 'Instructor'] }))
    .mockImplementationOnce(() => Promise.reject(new Error('Something went wrong'))),

  update (id: number, data: RoleModel): Promise<any> {
    return new Promise<any>((resolve, reject) => {
      if (id === 1) {
        resolve({
          data: {
            'id': id,
            'color': data.color,
            'deletable': false,
            'editable': false,
            'name': data.name,
            'created_at': '2020-03-15T20:00:00.000000Z',
            'updated_at': '2020-03-15T20:00:00.000000Z',
            'permissions': [
              {
                'slug': 'personal',
                'is_default': true,
                'name': 'Access to personal information',
                'created_at': '2020-03-15T20:00:00.000000Z',
                'updated_at': '2020-03-15T20:00:00.000000Z',
                'pivot': {
                  'role_id': 1,
                  'permission_slug': 'personal'
                }
              }
            ]
          },
          status: 200,
          statusText: 'OK',
          headers: {},
          config: {}
        });
      }

      const e = new Error('Something went wrong') as AxiosError;
      e.response = {
        data: { message: 'The request failed.' },
        status: 400,
        statusText: 'Bad Request',
        headers: {},
        config: {}
      };
      reject(e);
    });
  }
};
