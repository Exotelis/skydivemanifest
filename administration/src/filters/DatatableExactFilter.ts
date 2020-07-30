import { DatatableFilterInputModel, DatatableFilterSelectModel } from '@/models/datatable/DatatableFilterModels';
import { FilterType } from '@/enum/FilterType';
import DatatableBaseFilter from '@/filters/DatatableBaseFilter';

export default class DatatableExactFilter extends DatatableBaseFilter {
  constructor (label: string, exact: DatatableFilterInputModel|DatatableFilterSelectModel) {
    super(label, FilterType.exact, exact);
  }
}
