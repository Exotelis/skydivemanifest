import { FilterType } from '@/enum/FilterType';
import { FilterInputTypes } from '@/enum/FilterInputTypes';
import DatatableBaseFilter from '@/filters/DatatableBaseFilter';

describe('DatatableBaseFilter', () => {
  it('have label test', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter('test', FilterType.exact);
    expect(filter.getLabel()).toBe('test');
  });

  it('have type \'FilterType.exact\'', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter('test', FilterType.exact);
    expect(filter.getType()).toBe(FilterType.exact);
  });

  it('not have an exact item', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter('test', FilterType.exact);
    expect(filter.hasExact()).toBeFalsy();
  });

  it('have an exact item', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasExact()).toBeTruthy();
  });

  it('not have a exact label', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasExactLabel()).toBeFalsy();
  });

  it('have a exact label', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp', label: 'testLabel' }
    );
    expect(filter.hasExactLabel()).toBeTruthy();
  });

  it('return exact label', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp', label: 'testLabel' }
    );
    expect(filter.getExactLabel()).toBe('testLabel');
  });

  it('not return exact label', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.getExactLabel()).toBeUndefined();
  });

  it('not have a exact value', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasExactValue()).toBeFalsy();
  });

  it('have a exact value', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.hasExactValue()).toBeTruthy();
  });

  it('return exact value', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.getExactValue()).toBe('testValue');
  });

  it('not return exact value', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.getExactValue()).toBeUndefined();
  });

  it('return exact prop', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.getExactProp()).toBe('testProp');
  });

  it('not have a exact prop', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter('test', FilterType.exact);
    expect(filter.getExactProp()).toBeUndefined();
  });

  it('have any value', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.hasValue()).toBeTruthy();
  });

  it('not have any value', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact
    );
    expect(filter.hasValue()).toBeFalsy();
  });

  it('clear all values - but nothing to clear', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact
    );
    expect(filter.hasExactValue()).toBeFalsy();
    filter.clearValues();
    expect(filter.hasExactValue()).toBeFalsy();
  });

  it('clear all values', () => {
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.hasExactValue()).toBeTruthy();
    filter.clearValues();
    expect(filter.hasExactValue()).toBeFalsy();
  });

  it('find prop', () => {
    const exact: any = { inputType: FilterInputTypes.text, prop: 'testProp' };
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      exact
    );
    expect(filter.findProp('testProp')).toEqual(exact);
  });

  it('not find prop', () => {
    const exact: any = { inputType: FilterInputTypes.text, prop: 'testProp' };
    const filter: DatatableBaseFilter = new DatatableBaseFilter(
      'test',
      FilterType.exact,
      exact
    );
    expect(filter.findProp('testPropDoesNotExist')).toBeUndefined();
  });
});
