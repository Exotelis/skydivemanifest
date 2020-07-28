import { Position } from '@/enum/Position';

export interface DatatableColumnModel {
  alignBody?: Position,
  alignHead?: Position,
  classes?: string,
  hide?: boolean
  label: string
  notHideable?: boolean
  prop: string
  propCustom?: (params: any) => {}
  sortable?: boolean
  sortKey?: string
}
