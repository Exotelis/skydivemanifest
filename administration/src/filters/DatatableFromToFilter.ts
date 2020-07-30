import { BaseFilterInputModel, DatatableFilterInputModel } from '@/models/datatable/DatatableFilterModels';
import { FilterType } from '@/enum/FilterType';
import DatatableBaseFilter from '@/filters/DatatableBaseFilter';

export default class DatatableFromToFilter extends DatatableBaseFilter {
  from?: DatatableFilterInputModel;
  to?: DatatableFilterInputModel;

  constructor (
    label: string,
    exact?: DatatableFilterInputModel,
    from?: DatatableFilterInputModel,
    to?: DatatableFilterInputModel
  ) {
    super(label, FilterType.fromTo, exact);
    this.from = from;
    this.to = to;
  }

  clearFromToValues (): void {
    if (this.hasFromValue()) {
      this.from!.value = undefined;
    }

    if (this.hasToValue()) {
      this.to!.value = undefined;
    }
  }

  findProp (prop: string): BaseFilterInputModel|undefined {
    if (this.hasExact() && this.exact!.prop === prop) {
      return this.exact;
    }

    if (this.hasFrom() && this.from!.prop === prop) {
      return this.from;
    }

    if (this.hasTo() && this.to!.prop === prop) {
      return this.to;
    }

    return undefined;
  }

  clearValues (): void {
    if (this.hasExactValue()) {
      this.exact!.value = undefined;
    }

    if (this.hasFromValue()) {
      this.from!.value = undefined;
    }

    if (this.hasTo()) {
      this.to!.value = undefined;
    }
  }

  getFromLabel (): string|undefined {
    return this.hasFromLabel() ? this.from!.label : undefined;
  }

  getFromProp (): string|undefined {
    return this.hasFrom() ? this.from!.prop : undefined;
  }

  getFromValue (): string|undefined {
    return this.hasFromValue() ? this.from!.value : undefined;
  }

  getToLabel (): string|undefined {
    return this.hasToLabel() ? this.to!.label : undefined;
  }

  getToProp (): string|undefined {
    return this.hasTo() ? this.to!.prop : undefined;
  }

  getToValue (): string|undefined {
    return this.hasToValue() ? this.to!.value : undefined;
  }

  hasFrom (): boolean {
    return this.from !== undefined;
  }

  hasFromLabel (): boolean {
    return this.hasFrom() && this.from!.label !== undefined && this.from!.label !== '';
  }

  hasFromValue (): boolean {
    return this.hasFrom() && this.from!.value !== undefined && this.from!.value !== '';
  }

  hasTo (): boolean {
    return this.to !== undefined;
  }

  hasToLabel (): boolean {
    return this.hasTo() && this.to!.label !== undefined && this.to!.label !== '';
  }

  hasToValue (): boolean {
    return this.hasTo() && this.to!.value !== undefined && this.to!.value !== '';
  }

  hasFromToValues (): boolean {
    return this.hasFromValue() && this.hasToValue();
  }

  hasValue (): boolean {
    return this.hasExactValue() || this.hasFromValue() || this.hasToValue();
  }
}
