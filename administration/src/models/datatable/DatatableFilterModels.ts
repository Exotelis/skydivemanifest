import { FilterInputTypes } from '@/enum/FilterInputTypes';
import { Options } from '@/types/Options';

export interface BaseFilterInputModel {
  inputType: FilterInputTypes,
  label?: string,
  prop: string,
  value?: string
}

export interface DatatableFilterInputModel extends BaseFilterInputModel {
  readonly inputType: FilterInputTypes.date|FilterInputTypes.email|FilterInputTypes.number|FilterInputTypes.text,
}

export interface DatatableFilterSelectModel extends BaseFilterInputModel {
  readonly inputType: FilterInputTypes.select,
  options: Options
}
