import axios from 'axios';

export default {
  async all (): Promise<any> {
    return axios.get('/permissions');
  }
};
