import { config, shallowMount } from '@vue/test-utils';
import DatatableRowsPerPage from '@/components/datatable/DatatableRowsPerPage.vue';

config.mocks!.$t = (key: any) => key;

const factory = (props = {}) => {
  return shallowMount(DatatableRowsPerPage, {
    propsData: {
      ...props
    }
  });
};

describe('DatatableRowsPerPage.vue', () => {
  it('is Vue instance', () => {
    const wrapper: any = factory({ current: 50, rowsPerPage: [10, 25, 50, 100, 250] });
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('define uuid', () => {
    const wrapper: any = factory({ current: 50, rowsPerPage: [10, 25, 50, 100, 250] });
    expect(wrapper.vm.uuid).not.toBe('');
  });

  it('change rows per page and fire an event', async () => {
    const wrapper: any = factory({ current: 50, rowsPerPage: [10, 25, 50, 100, 250] });
    wrapper.vm.changePerRow(100);

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('datatable:rowsPerPageChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:rowsPerPageChanged')[0]).toEqual([100]);
  });

  it('change rows per page and fire events when select box has been changed', async () => {
    const wrapper: any = factory({ current: 50, rowsPerPage: [10, 25, 50, 100, 250] });
    let select = wrapper.find('.datatable_rowsPerPage').element;
    select.value = 250;
    wrapper.find('.datatable_rowsPerPage').trigger('change');

    await wrapper.vm.$nextTick();

    expect(wrapper.emitted('datatable:rowsPerPageChanged')).toBeTruthy();
    expect(wrapper.emitted('datatable:rowsPerPageChanged')[0]).toEqual([250]);
    expect(wrapper.emitted('update:current')).toBeTruthy();
    expect(wrapper.emitted('update:current')[0]).toEqual([250]);
  });

  it('call method \'determineNewCurrent\' when \'current\' is not in \'rowsPerPage\'', () => {
    const wrapper: any = factory({ current: 7, rowsPerPage: [10, 25, 50, 100, 250] });

    expect(wrapper.emitted('update:current')).toBeTruthy();
    expect(wrapper.emitted('update:current')[0]).toEqual([10]);
  });

  it('check if \'determineNewCurrent\' returns min value', () => {
    const wrapper: any = factory({ current: 7, rowsPerPage: [10, 25, 50, 100, 250] });
    let value = wrapper.vm.determineNewCurrent(7);

    expect(value).toBe(10);
  });

  it('check if \'determineNewCurrent\' returns max value', () => {
    const wrapper: any = factory({ current: 280, rowsPerPage: [10, 25, 50, 100, 250] });
    let value = wrapper.vm.determineNewCurrent(280);

    expect(value).toBe(250);
  });

  it('check if \'determineNewCurrent\' returns the nearest value', () => {
    const wrapper: any = factory({ current: 75, rowsPerPage: [10, 25, 50, 100, 250] });
    let value = wrapper.vm.determineNewCurrent(50);

    expect(value).toBe(50);
  });
});
