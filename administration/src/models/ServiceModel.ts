export interface ServiceModel {
  (id?: any, data?: any, params?: object): Promise<any>;
}
