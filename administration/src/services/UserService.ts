import axios from 'axios';

export default {
  async all (params?: object): Promise<any> {
    return axios.get('/users', { params: params });
  },

  async bulkDelete (ids: Array<number>): Promise<any> {
    return axios.delete('/users', { params: { ids: ids } });
  },

  async delete (id: number): Promise<any> {
    return axios.delete('/users/' + id);
  },

  async deletePermanently (ids: Array<number>): Promise<any> {
    return axios.delete('/users/trashed', { params: { ids: ids } });
  },

  async restore (ids: Array<number>): Promise<any> {
    return axios.put('/users/trashed', { ids: ids });
  },

  async trashed (params?: object): Promise<any> {
    return axios.get('/users/trashed', { params: params });
  }
};
