import axios from 'axios';
import RoleModel from '@/models/RoleModel';

export default {
  async add (data: RoleModel): Promise<any> {
    return axios.post('/roles', data);
  },

  async all (params?: object): Promise<any> {
    return axios.get('/roles', { params: params });
  },

  async bulkDelete (ids: Array<number>): Promise<any> {
    return axios.delete('/roles', { params: { ids: ids } });
  },

  async delete (id: number): Promise<any> {
    return axios.delete('/roles/' + id);
  },

  async get (id: number): Promise<any> {
    return axios.get('/roles/' + id);
  },

  async names (): Promise<any> {
    return axios.get('/roles/names');
  },

  async update (id: number, data: RoleModel): Promise<any> {
    return axios.put('/roles/' + id, data);
  }
};
