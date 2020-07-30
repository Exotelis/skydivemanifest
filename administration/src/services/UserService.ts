import axios from 'axios';

export default {
  async all (params?: object): Promise<any> {
    return axios.get('/users', { params: params });
  }
};
