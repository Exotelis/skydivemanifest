/* eslint camelcase: 0 */

export interface DatatableDataModel {
  current_page: number,
  data: Array<object>|null,
  first_page_url?: string,
  from: null|number,
  last_page: number,
  last_page_url?: string,
  next_page_url?: null|string,
  path?: string,
  per_page: number|string,
  prev_page_url?: null|string,
  to: null|number,
  total: number
}
