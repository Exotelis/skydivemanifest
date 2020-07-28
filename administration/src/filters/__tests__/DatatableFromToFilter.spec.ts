import { FilterType } from '@/enum/FilterType';
import { FilterInputTypes } from '@/enum/FilterInputTypes';
import DatatableFromToFilter from '@/filters/DatatableFromToFilter';

describe('DatatableFromToFilter', () => {
  it('always have type \'FilterType.fromTo\'', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.getType()).toBe(FilterType.fromTo);
  });

  it('not have an from item', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter('test');
    expect(filter.hasFrom()).toBeFalsy();
  });

  it('not have an to item', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter('test');
    expect(filter.hasTo()).toBeFalsy();
  });

  it('have an from item', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasFrom()).toBeTruthy();
  });

  it('have an to item', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasTo()).toBeTruthy();
  });

  it('not have a from value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasFromLabel()).toBeFalsy();
  });

  it('not have a to value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasToLabel()).toBeFalsy();
  });

  it('have a from value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', label: 'testLabel' }
    );
    expect(filter.hasFromLabel()).toBeTruthy();
  });

  it('have a to label', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', label: 'testLabel' }
    );
    expect(filter.hasToLabel()).toBeTruthy();
  });

  it('return from label', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', label: 'testLabel' }
    );
    expect(filter.getFromLabel()).toBe('testLabel');
  });

  it('return to label', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', label: 'testLabel' }
    );
    expect(filter.getToLabel()).toBe('testLabel');
  });

  it('not return a from label', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.getFromLabel()).toBeUndefined();
  });

  it('not return a to label', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.getToLabel()).toBeUndefined();
  });

  it('not have a from value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasFromValue()).toBeFalsy();
  });

  it('not have a to value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasToValue()).toBeFalsy();
  });

  it('have a from value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.hasFromValue()).toBeTruthy();
  });

  it('have a to value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.hasToValue()).toBeTruthy();
  });

  it('return from prop', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.getFromProp()).toBe('testProp');
  });

  it('return to prop', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.getToProp()).toBe('testProp');
  });

  it('not have a from prop', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter('test');
    expect(filter.getFromProp()).toBeUndefined();
  });

  it('not have a to prop', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter('test');
    expect(filter.getToProp()).toBeUndefined();
  });

  it('return from value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.getFromValue()).toBe('testValue');
  });

  it('return to value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.getToValue()).toBe('testValue');
  });

  it('not return a from value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.getFromValue()).toBeUndefined();
  });

  it('not return a to value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.getToValue()).toBeUndefined();
  });

  it('not have a fromTo value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' },
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.hasFromToValues()).toBeFalsy();
  });

  it('have a fromTo value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' },
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.hasFromToValues()).toBeTruthy();
  });

  it('have any value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.hasValue()).toBeTruthy();
  });

  it('not have any value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test'
    );
    expect(filter.hasValue()).toBeFalsy();
  });

  it('clear from value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.hasFromValue()).toBeTruthy();
    filter.clearFromToValues();
    expect(filter.hasFromValue()).toBeFalsy();
  });

  it('clear to value', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' }
    );
    expect(filter.hasToValue()).toBeTruthy();
    filter.clearFromToValues();
    expect(filter.hasToValue()).toBeFalsy();
  });

  it('clear from and to values', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      { inputType: FilterInputTypes.text, prop: 'testPropFrom', value: 'testValueFrom' },
      { inputType: FilterInputTypes.text, prop: 'testPropTo', value: 'testValueTo' }
    );
    expect(filter.hasFromValue()).toBeTruthy();
    expect(filter.hasToValue()).toBeTruthy();
    filter.clearFromToValues();
    expect(filter.hasFromValue()).toBeFalsy();
    expect(filter.hasToValue()).toBeFalsy();
  });

  it('clear all values', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      { inputType: FilterInputTypes.text, prop: 'testPropExact', value: 'testValueExact' },
      { inputType: FilterInputTypes.text, prop: 'testPropFrom', value: 'testValueFrom' },
      { inputType: FilterInputTypes.text, prop: 'testPropTo', value: 'testValueTo' }
    );
    expect(filter.hasExactValue()).toBeTruthy();
    expect(filter.hasFromValue()).toBeTruthy();
    expect(filter.hasToValue()).toBeTruthy();
    filter.clearValues();
    expect(filter.hasExactValue()).toBeFalsy();
    expect(filter.hasFromValue()).toBeFalsy();
    expect(filter.hasToValue()).toBeFalsy();
  });

  it('clear all values - but nothing to clear', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test'
    );
    expect(filter.hasExactValue()).toBeFalsy();
    filter.clearValues();
    expect(filter.hasExactValue()).toBeFalsy();
  });

  it('find exact prop', () => {
    const exact: any = { inputType: FilterInputTypes.text, prop: 'testProp' };
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      exact
    );
    expect(filter.findProp('testProp')).toEqual(exact);
  });

  it('find from prop', () => {
    const from: any = { inputType: FilterInputTypes.text, prop: 'testProp' };
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      from
    );
    expect(filter.findProp('testProp')).toEqual(from);
  });

  it('find to prop', () => {
    const to: any = { inputType: FilterInputTypes.text, prop: 'testProp' };
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test',
      undefined,
      undefined,
      to
    );
    expect(filter.findProp('testProp')).toEqual(to);
  });

  it('not find prop', () => {
    const filter: DatatableFromToFilter = new DatatableFromToFilter(
      'test'
    );
    expect(filter.findProp('testProp')).toBeUndefined();
  });
});
