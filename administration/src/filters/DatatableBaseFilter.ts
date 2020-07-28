import { BaseFilterInputModel, DatatableFilterInputModel, DatatableFilterSelectModel } from
  '@/models/datatable/DatatableFilterModels';
import { FilterType } from '@/enum/FilterType';

export default class DatatableBaseFilter {
  exact?: DatatableFilterInputModel|DatatableFilterSelectModel;
  readonly label: string;
  readonly type: FilterType;

  constructor (label: string, type: FilterType, exact?: DatatableFilterInputModel|DatatableFilterSelectModel) {
    this.label = label;
    this.exact = exact;
    this.type = type;
  }

  clearValues (): void {
    if (this.hasExactValue()) {
      this.exact!.value = undefined;
    }
  }

  findProp (prop: string): BaseFilterInputModel|undefined {
    if (this.hasExact() && this.exact!.prop === prop) {
      return this.exact;
    }

    return undefined;
  }

  getExactLabel (): string|undefined {
    return this.hasExactLabel() ? this.exact!.label : undefined;
  }

  getExactProp (): string|undefined {
    return this.hasExact() ? this.exact!.prop : undefined;
  }

  getExactValue (): string|undefined {
    return this.hasExactValue() ? this.exact!.value : undefined;
  }

  getLabel (): string {
    return this.label;
  }

  getType (): FilterType {
    return this.type;
  }

  hasExact (): boolean {
    return this.exact !== undefined;
  }

  hasExactLabel (): boolean {
    return this.hasExact() && this.exact!.label !== undefined && this.exact!.label !== '';
  }

  hasExactValue (): boolean {
    return this.hasExact() && this.exact!.value !== undefined && this.exact!.value !== '';
  }

  hasValue (): boolean {
    return this.hasExactValue();
  }
}
