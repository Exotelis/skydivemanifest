import { config, shallowMount } from '@vue/test-utils';
import { DatatableColumnModel } from '@/models/datatable/DatatableColumnModel';
import DatatableColumnSelection from '@/components/datatable/DatatableColumnSelection.vue';

config.mocks!.$t = (key: any) => key;

const factory = (props = {}) => {
  return shallowMount(DatatableColumnSelection, {
    propsData: {
      ...props
    },
    stubs: ['i18n']
  });
};

describe('DatatableColumnSelection.vue', () => {
  let columns: Array<DatatableColumnModel> = [
    { label: 'Test #', prop: 'id', notHideable: true },
    { label: 'Email address', prop: 'email', hide: true },
    { label: 'Lastname', prop: 'lastname' },
    { label: 'Firstname', prop: 'firstname' }
  ];
  let visible: Array<string> = columns.filter(column => !column.hide).map(column => column.prop);

  afterEach(() => {
    localStorage.clear();
  });

  it('is Vue instance', () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check default values of DatatableColumnSelection', () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests' });
    expect(wrapper.props().max).toBe(10);
    expect(wrapper.props().visible).toEqual([]);
  });

  it('set defaults', () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });
    expect(wrapper.vm.default).toStrictEqual(visible);
  });

  it('define uuid', () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });
    expect(wrapper.vm.uuid).not.toBe('');
  });

  it('not emit event on creation without local storage entry', () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });

    expect(wrapper.emitted('datatable:columnToggle')).toBeFalsy();
  });

  it('emit events on creation with local storage entry', () => {
    const obj: any = { 'columnSelection': JSON.stringify(['firstname']) };
    localStorage.setItem('datatable_tests', JSON.stringify(obj));
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });

    expect(wrapper.emitted('update:visible').length).toBe(1);
    expect(wrapper.emitted('update:visible')).toBeTruthy();
    expect(wrapper.emitted('update:visible')[0]).toEqual([['firstname']]);
    expect(wrapper.emitted('datatable:columnToggle').length).toBe(1);
    expect(wrapper.emitted('datatable:columnToggle')).toBeTruthy();
    expect(wrapper.emitted('datatable:columnToggle')[0]).toEqual([['firstname']]);
  });

  it('reset to defaults', () => {
    const obj: any = { 'columnSelection': JSON.stringify(['id']) };
    localStorage.setItem('datatable_tests', JSON.stringify(obj));
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });

    expect(wrapper.emitted('datatable:columnToggle').length).toBe(1);
    expect(wrapper.emitted('update:visible').length).toBe(1);

    wrapper.vm.resetToDefaults();

    expect(wrapper.emitted('datatable:columnToggle').length).toBe(2);
    expect(wrapper.emitted('update:visible').length).toBe(2);
    expect(wrapper.emitted('datatable:columnToggle')[1]).toEqual([wrapper.vm.default]);
    expect(wrapper.emitted('update:visible')[1]).toEqual([wrapper.vm.default]);
    const storage: any = JSON.parse(localStorage.getItem('datatable_tests')!)['columnSelection'];
    expect(storage).toBe(JSON.stringify(wrapper.vm.default));
  });

  it('not toggle with invalid event', () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });
    const event = new Event('test');

    wrapper.vm.toggleColumn(event);

    expect(wrapper.emitted('datatable:columnToggle')).toBeFalsy();
  });

  it('not toggle if last visible column', async () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: ['firstname'] });
    const spy = jest.spyOn(wrapper.vm, 'toggleColumn');
    const selector = wrapper.find('#datatable_columnSelection-firstname-' + wrapper.vm.uuid);

    expect(selector.element.disabled).toBeTruthy();
    selector.element.disabled = false;
    selector.trigger('change');

    await wrapper.vm.$nextTick();

    expect(spy).toHaveBeenCalled();
    expect(selector.element.checked).toBeTruthy();
    expect(wrapper.emitted('datatable:columnToggle')).toBeFalsy();

    spy.mockRestore();
  });

  it('not toggle column if already \'max\' columns are selected', async () => {
    const wrapper: any = factory({ columns: columns, max: 2, tableId: 'tests', visible: visible });
    const spy = jest.spyOn(wrapper.vm, 'toggleColumn');
    const selector = wrapper.find('#datatable_columnSelection-email-' + wrapper.vm.uuid);

    expect(selector.element.disabled).toBeTruthy();
    selector.element.disabled = false;
    selector.trigger('change');

    await wrapper.vm.$nextTick();

    expect(spy).toHaveBeenCalled();
    expect(selector.element.checked).toBeFalsy();
    expect(wrapper.emitted('datatable:columnToggle')).toBeFalsy();

    spy.mockRestore();
  });

  it('toggle column - show email', async () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });
    const spy = jest.spyOn(wrapper.vm, 'toggleColumn');

    wrapper.find('#datatable_columnSelection-email-' + wrapper.vm.uuid).trigger('change');

    await wrapper.vm.$nextTick();

    expect(spy).toHaveBeenCalled();

    expect(wrapper.emitted('datatable:columnToggle')).toBeTruthy();
    expect(wrapper.emitted('datatable:columnToggle')[0]).toEqual([['id', 'lastname', 'firstname', 'email']]);

    expect(wrapper.emitted('update:visible')).toBeTruthy();
    expect(wrapper.emitted('update:visible')[0]).toEqual([['id', 'lastname', 'firstname', 'email']]);

    const storage: any = JSON.parse(localStorage.getItem('datatable_tests')!)['columnSelection'];
    expect(storage).toBe(JSON.stringify(['id', 'lastname', 'firstname', 'email']));

    spy.mockRestore();
  });

  it('toggle column - hide lastname', async () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });
    const spy = jest.spyOn(wrapper.vm, 'toggleColumn');

    wrapper.find('#datatable_columnSelection-lastname-' + wrapper.vm.uuid).trigger('change');

    await wrapper.vm.$nextTick();

    expect(spy).toHaveBeenCalled();

    expect(wrapper.emitted('datatable:columnToggle')).toBeTruthy();
    expect(wrapper.emitted('datatable:columnToggle')[0]).toEqual([['id', 'firstname']]);

    expect(wrapper.emitted('update:visible')).toBeTruthy();
    expect(wrapper.emitted('update:visible')[0]).toEqual([['id', 'firstname']]);

    const storage: any = JSON.parse(localStorage.getItem('datatable_tests')!)['columnSelection'];
    expect(storage).toBe(JSON.stringify(['id', 'firstname']));

    spy.mockRestore();
  });

  it('display the correct number of shown columns', () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });

    expect(wrapper.find('.datatable_columnSelection-shown').text()).toBe('3 / 10');
  });

  it('exclude \'notHideable\' columns from list', () => {
    const wrapper: any = factory({ columns: columns, tableId: 'tests', visible: visible });

    expect(wrapper.find('#datatable_columnSelection-id-' + wrapper.vm.uuid).exists()).toBeFalsy();
  });
});
