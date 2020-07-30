import { FilterType } from '@/enum/FilterType';
import { FilterInputTypes } from '@/enum/FilterInputTypes';
import DatatableExactFilter from '@/filters/DatatableExactFilter';

describe('DatatableExactFilter', () => {
  it('always have type \'FilterType.exact\'', () => {
    const filter: DatatableExactFilter = new DatatableExactFilter(
      'test',
      { inputType: FilterInputTypes.text, prop: 'testProp' }
    );
    expect(filter.getType()).toBe(FilterType.exact);
  });
});
