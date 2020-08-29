import axios from 'axios';

export default {
  async all (params?: object): Promise<any> {
    return axios.get('/roles', { params: params });
  },

  async bulkDelete (ids: Array<number>): Promise<any> {
    return axios.delete('/roles', { params: { ids: ids } });
  },

  async names (): Promise<any> {
    return axios.get('/roles/names');
  }
};
