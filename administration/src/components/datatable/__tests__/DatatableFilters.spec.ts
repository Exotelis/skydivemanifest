import { config, mount } from '@vue/test-utils';
import { FilterInputTypes } from '@/enum/FilterInputTypes';
import DatatableFilters from '@/components/datatable/DatatableFilters.vue';
import DatatableBaseFilter from '@/filters/DatatableBaseFilter';
import DatatableExactFilter from '@/filters/DatatableExactFilter';
import DatatableFromToFilter from '@/filters/DatatableFromToFilter';

config.mocks!.$t = (key: any) => key;

const factory = (props = {}) => {
  return mount(DatatableFilters, {
    propsData: {
      ...props
    }
  });
};

describe('DatatableFilters.vue', () => {
  it('is Vue instance', () => {
    const wrapper: any = factory();
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check default values of DatatableFilters', () => {
    const wrapper: any = factory();
    expect(wrapper.props().filters).toEqual([]);
    expect(wrapper.props().filtersVisible).toBeFalsy();
  });

  it('clone filters to filterModel', () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter('Test', { inputType: FilterInputTypes.text, prop: 'testProp' })
    ];
    const wrapper: any = factory({ filters: filters });
    expect(wrapper.vm.filters).toEqual(wrapper.vm.filterModel);
    expect(wrapper.props().filtersVisible).toBeFalsy();
  });

  it('have one filter assigned', () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter('Test', { inputType: FilterInputTypes.text, prop: 'testProp' })
    ];
    const wrapper: any = factory({ filters: filters });
    expect(wrapper.props().filters).toEqual(filters);
  });

  it('change prop filtersVisible to true', () => {
    const wrapper: any = factory({ filtersVisible: true });
    expect(wrapper.props().filtersVisible).toBeTruthy();
  });

  it('close filters panel', async () => {
    const wrapper: any = factory({ filtersVisible: true });

    wrapper.find('#datatable_filters-cancel-' + wrapper.vm.uuid).trigger('click');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('update:filtersVisible')[0]).toEqual([false]);
  });

  it('have caption and custom labels', () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableFromToFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testExactProp', label: 'testExactLabel' },
        { inputType: FilterInputTypes.text, prop: 'testFromProp', label: 'testFromLabel' },
        { inputType: FilterInputTypes.text, prop: 'testToProp', label: 'testToLabel' }
      )
    ];
    const wrapper: any = factory({ filters: filters, filtersVisible: true });

    expect(wrapper.find('legend').text()).toBe('Test');
    expect(wrapper.find('.datatable_filters-exact-formGroup label').text()).toBe('testExactLabel');
    expect(wrapper.find('.datatable_filters-from-formGroup label').text()).toBe('testFromLabel');
    expect(wrapper.find('.datatable_filters-to-formGroup label').text()).toBe('testToLabel');
  });

  it('call applyFilters', async () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter('Test', { inputType: FilterInputTypes.text, prop: 'testProp' })
    ];
    const wrapper: any = factory({ filters: filters, filtersVisible: true });
    const spy = jest.spyOn(wrapper.vm, 'applyFilters');

    wrapper.find('form').trigger('submit');

    await wrapper.vm.$nextTick();

    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('apply filters, emit event and close filters panel', async () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter('Test', { inputType: FilterInputTypes.text, prop: 'testProp' })
    ];
    const wrapper: any = factory({ filters: filters, filtersVisible: true });

    wrapper.find('#datatable_filters-exact-testProp-' + wrapper.vm.uuid).setValue('10');
    wrapper.find('form').trigger('submit');

    await wrapper.vm.$nextTick();

    filters[0].exact!.value = '10';
    expect(wrapper.emitted('datatable:filtersChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:filtersChanged')[0]).toEqual([filters]);
    expect(wrapper.emitted('update:filtersVisible')[0]).toEqual([false]);
  });

  it('apply filters, but clear from value', async () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableFromToFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testExactProp' },
        { inputType: FilterInputTypes.text, prop: 'testFromProp' }
      )
    ];
    const wrapper: any = factory({ filters: filters, filtersVisible: true });

    filters[0].exact!.value = '10';
    wrapper.find('#datatable_filters-exact-testExactProp-' + wrapper.vm.uuid).setValue('10');
    wrapper.find('#datatable_filters-from-testFromProp-' + wrapper.vm.uuid).setValue('1');
    wrapper.find('form').trigger('submit');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('datatable:filtersChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:filtersChanged')[0]).toEqual([filters]);
    expect(wrapper.emitted('update:filtersVisible')[0]).toEqual([false]);
  });

  it('apply filters, but clear to value', async () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableFromToFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testExactProp' },
        undefined,
        { inputType: FilterInputTypes.text, prop: 'testToProp' }
      )
    ];
    const wrapper: any = factory({ filters: filters, filtersVisible: true });

    filters[0].exact!.value = '10';
    wrapper.find('#datatable_filters-exact-testExactProp-' + wrapper.vm.uuid).setValue('10');
    wrapper.find('#datatable_filters-to-testToProp-' + wrapper.vm.uuid).setValue('1');
    wrapper.find('form').trigger('submit');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('datatable:filtersChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:filtersChanged')[0]).toEqual([filters]);
    expect(wrapper.emitted('update:filtersVisible')[0]).toEqual([false]);
  });

  it('apply filters, but clear from and to values', async () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableFromToFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testExactProp' },
        { inputType: FilterInputTypes.text, prop: 'testFromProp' },
        { inputType: FilterInputTypes.text, prop: 'testToProp' }
      )
    ];
    const wrapper: any = factory({ filters: filters, filtersVisible: true });

    filters[0].exact!.value = '10';
    wrapper.find('#datatable_filters-exact-testExactProp-' + wrapper.vm.uuid).setValue('10');
    wrapper.find('#datatable_filters-from-testFromProp-' + wrapper.vm.uuid).setValue('1');
    wrapper.find('#datatable_filters-to-testToProp-' + wrapper.vm.uuid).setValue('2');
    wrapper.find('form').trigger('submit');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('datatable:filtersChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:filtersChanged')[0]).toEqual([filters]);
    expect(wrapper.emitted('update:filtersVisible')[0]).toEqual([false]);
  });

  it('have active filters', () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testProp', value: 'TestValue' }
      )
    ];
    const wrapper: any = factory({ filters: filters, filtersVisible: true });

    expect(wrapper.vm.hasActiveFilter()).toBeTruthy();
  });

  it('not have active filters', () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter('Test', { inputType: FilterInputTypes.text, prop: 'testProp' })
    ];
    const wrapper: any = factory({ filters: filters, filtersVisible: true });

    expect(wrapper.vm.hasActiveFilter()).toBeFalsy();
  });

  it('add a badge for each filter', () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testProp', value: 'TestValue' }
      ),
      new DatatableExactFilter('Test2',
        { inputType: FilterInputTypes.text, prop: 'testProp2', value: 'TestValue' }
      )
    ];
    const wrapper: any = factory({ filters: filters });

    expect(wrapper.findAll('.badge').length).toBe(2);
  });

  it('clear a specific filter', async () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testProp', value: 'TestValue' }
      )
    ];
    const wrapper: any = factory({ filters: filters });

    expect(wrapper.vm.filterModel[0].getExactValue()).toBe('TestValue');
    wrapper.vm.clearFilter(0);
    expect(wrapper.vm.filterModel[0].getExactValue()).toBeUndefined();
  });

  it('clear all filters', async () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testProp', value: 'TestValue' }
      ),
      new DatatableExactFilter('Test2',
        { inputType: FilterInputTypes.text, prop: 'testProp2', value: 'TestValue2' }
      )
    ];
    const wrapper: any = factory({ filters: filters });

    expect(wrapper.vm.filterModel[0].getExactValue()).toBe('TestValue');
    expect(wrapper.vm.filterModel[1].getExactValue()).toBe('TestValue2');
    wrapper.vm.clearFilters();
    expect(wrapper.vm.filterModel[0].getExactValue()).toBeUndefined();
    expect(wrapper.vm.filterModel[1].getExactValue()).toBeUndefined();
  });

  it('update filterModel on filters change', async () => {
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter('Test', { inputType: FilterInputTypes.text, prop: 'testProp' })
    ];
    const wrapper: any = factory({ filters: filters });

    filters[0].exact!.value = '10';
    expect(wrapper.vm.filterModel[0].hasExactValue()).toBeFalsy();
    wrapper.vm.onFiltersUpdate();
    expect(wrapper.vm.filterModel[0].hasExactValue()).toBeTruthy();
  });
});
