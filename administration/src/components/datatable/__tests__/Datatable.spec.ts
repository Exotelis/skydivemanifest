import { config, shallowMount } from '@vue/test-utils';
import { colorYiq } from '@/helpers';
import { DatatableActionModel } from '@/models/datatable/DatatableActionModel';
import { DatatableColumnModel } from '@/models/datatable/DatatableColumnModel';
import { FilterInputTypes } from '@/enum/FilterInputTypes';
import { Density } from '@/enum/Density';
import { Position } from '@/enum/Position';
import { ServiceModel } from '@/models/ServiceModel';
import { SortMode } from '@/enum/SortMode';
import Datatable from '@/components/datatable/Datatable.vue';
import DatatableBaseFilter from '@/filters/DatatableBaseFilter';
import DatatableExactFilter from '@/filters/DatatableExactFilter';
import DatatableFromToFilter from '@/filters/DatatableFromToFilter';
import flushPromises from 'flush-promises';
import UserService from '@/services/UserService';

jest.mock('@/services/UserService');

config.mocks!.$t = (key: any) => key;

const factory = (props = {}, query = {}, methods = {}) => {
  return shallowMount(Datatable, {
    methods: {
      ...methods
    },
    mocks: {
      $router: {
        currentRoute: {
          query: {
            ...query
          }
        },
        push: (params: any) => {
          return params;
        },
        replace: (params: any) => {
          return params;
        }
      }
    },
    propsData: {
      ...props
    }
  });
};

describe('Datatable.vue', () => {
  let columns!: Array<DatatableColumnModel>;
  let filterConfig!: Array<DatatableBaseFilter>;
  let service!: ServiceModel;
  let tableId!: string;
  let rolePropCustom = function ({ name, color }: any): string {
    let fontColor: string = colorYiq(`${color}`);
    return `<span style="background-color: ${color};color: ` + fontColor + `" class="badge">${name}</span>`;
  };
  let isActivePropCustom = function (isActive: boolean): string {
    return isActive
      ? '<span class="mdi mdi-circle-medium text-success"></span> enabled'
      : '<span class="mdi mdi-circle-medium text-danger"></span> disabled';
  };

  beforeEach(() => {
    columns = [
      { label: 'ID', prop: 'id', sortable: true },
      { label: 'Email', prop: 'email', classes: 'user-select-all', sortable: true },
      { label: 'First name', prop: 'firstname', alignBody: Position.center, alignHead: Position.center },
      { label: 'Last name', prop: 'lastname', alignBody: Position.right, alignHead: Position.right },
      { label: 'Active', prop: 'is_active', propCustom: isActivePropCustom },
      { label: 'Role', prop: 'role', propCustom: rolePropCustom, sortable: true, sortKey: 'role' },
      { label: 'Middle name', prop: 'middlename', hide: true }
    ];
    filterConfig = [
      new DatatableFromToFilter(
        'Id', { inputType: FilterInputTypes.text, prop: 'id', value: '1' }
      ),
      new DatatableExactFilter(
        'Name', { inputType: FilterInputTypes.text, prop: 'name' }
      ),
      new DatatableExactFilter(
        'Email', { inputType: FilterInputTypes.text, prop: 'email', value: 'exotelis@mailbox.org' }
      ),
      new DatatableFromToFilter(
        'Age',
        { inputType: FilterInputTypes.text, prop: 'age' },
        { inputType: FilterInputTypes.text, prop: 'age_from', value: '10' },
        { inputType: FilterInputTypes.text, prop: 'age_to', value: '20' }
      ),
      new DatatableFromToFilter(
        'Dob',
        { inputType: FilterInputTypes.text, prop: 'dob' },
        { inputType: FilterInputTypes.text, prop: 'dob_from', value: '1970-01-01' },
        { inputType: FilterInputTypes.text, prop: 'dob_to' }
      ),
      new DatatableFromToFilter(
        'Size',
        { inputType: FilterInputTypes.text, prop: 'size' },
        { inputType: FilterInputTypes.text, prop: 'size_from' },
        { inputType: FilterInputTypes.text, prop: 'size_to', value: '180' }
      )
    ];
    service = UserService.all;
    tableId = 'Test';
  });

  it('is Vue instance', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  /**
   *  Prop tests
   */

  it('check default values of Datatable', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    expect(wrapper.props().actions).toEqual([]);
    expect(wrapper.props().bulkActions).toEqual([]);
    expect(wrapper.props().caption).toBe('');
    expect(wrapper.props().filterConfig).toEqual([]);
    expect(wrapper.props().hideUtilityBarBottom).toBeFalsy();
    expect(wrapper.props().hideUtilityBarTop).toBeFalsy();
    expect(wrapper.props().historyMode).toBeFalsy();
    expect(wrapper.props().perPage).toEqual([10, 25, 50, 100, 250]);
    expect(wrapper.props().selectable).toBeFalsy();
    expect(wrapper.props().utilityBarBottomClasses).toBe('d-flex');
    expect(wrapper.props().utilityBarTopClasses).toBe('d-flex flex-column bg-white');
  });

  it('display column actions', () => {
    const actions: Array<DatatableActionModel> = [
      { label: 'Show', eventId: 'show', icon: 'mdi-eye' }
    ];
    const wrapper: any = factory({ actions: actions, columns: columns, service: service, tableId: tableId });

    expect(wrapper.findAll('thead .datatable_column-labels th').length).toBe(7);
  });

  it('display caption', () => {
    const wrapper: any = factory({ caption: 'TestTable', columns: columns, service: service, tableId: tableId });

    expect(wrapper.find('caption').text()).toBe('TestTable');
  });

  it('hide utility bar bottom', () => {
    const wrapper: any = factory(
      { columns: columns, hideUtilityBarBottom: true, service: service, tableId: tableId }
    );

    expect(wrapper.find('.datatable_utility-bar-bottom').exists()).toBeFalsy();
  });

  it('hide utility bar top', () => {
    const wrapper: any = factory(
      { columns: columns, hideUtilityBarTop: true, service: service, tableId: tableId }
    );

    expect(wrapper.find('.datatable_utility-bar-top').exists()).toBeFalsy();
  });

  it('enable the history mode', () => {
    const wrapper: any = factory({ columns: columns, historyMode: true, service: service, tableId: tableId });

    expect(wrapper.props().historyMode).toBeTruthy();
  });

  it('change the \'perPage\' prop', () => {
    const wrapper: any = factory({ columns: columns, perPage: [5, 50, 500], service: service, tableId: tableId });

    expect(wrapper.props().perPage).toEqual([5, 50, 500]);
  });

  it('enable the selectable mode', async () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });

    await flushPromises();

    expect(wrapper.findAll('thead .datatable_column-labels th').length).toBe(7);
    expect(wrapper.findAll('tbody tr td').at(0).classes()).toContain('datatable_selection');
  });

  it('set custom classes on bottom utility bar', () => {
    const wrapper: any = factory(
      { columns: columns, service: service, tableId: tableId, utilityBarBottomClasses: 'test-class' }
    );

    expect(wrapper.find('.datatable_utility-bar-bottom div').classes()).toContain('test-class');
  });

  it('set custom classes on top utility bar', () => {
    const wrapper: any = factory(
      { columns: columns, service: service, tableId: tableId, utilityBarTopClasses: 'test-class' }
    );

    expect(wrapper.find('.datatable_utility-bar-top div').classes()).toContain('test-class');
  });

  /**
   * Methods tests
   */

  it('call \'getData\' on creation', () => {
    const getData = jest.fn();
    factory({ columns: columns, service: service, tableId: tableId }, {}, { getData: getData });

    expect(getData).toHaveBeenCalled();
  });

  it('call \'getData\' on creation when history mode is enabled', () => {
    const getData = jest.fn();
    factory(
      { columns: columns, historyMode: true, service: service, tableId: tableId },
      {},
      { getData: getData }
    );

    expect(getData).toHaveBeenCalled();
  });

  it('call deserializeFilters and update params when browser back or forward button has been used', () => {
    const deserializeFilters = jest.fn();
    const wrapper: any = factory(
      { columns: columns, historyMode: true, service: service, tableId: tableId },
      { limit: 50, page: 1, sort: 'id' },
      { deserializeFilters: deserializeFilters }
    );

    expect(wrapper.vm.params).toEqual({ limit: 50, page: 1, sort: 'id' });
    expect(deserializeFilters).toHaveBeenCalled();
  });

  it('update the current page if the selected page does not exist', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    await flushPromises();

    wrapper.vm.params.page = 50;
    wrapper.vm.getData();

    await flushPromises();

    expect(wrapper.vm.params).toEqual({ limit: 50, page: 3, sort: '' });
  });

  it('update current route when the current selected page does not exist', async () => {
    const wrapper: any = factory({ columns: columns, historyMode: true, service: service, tableId: tableId });
    const spy = jest.spyOn(wrapper.vm.$router, 'replace');

    await flushPromises();

    wrapper.vm.params.page = 50;
    wrapper.vm.getData();

    await flushPromises();

    expect(spy).toHaveReturnedWith({ path: undefined, query: { limit: 50, page: 3, sort: '' } });

    spy.mockRestore();
  });

  it('return error when trying to get data', async () => {
    const wrapper: any = factory({ columns: columns, historyMode: true, service: service, tableId: tableId });
    const spy = jest.spyOn(wrapper.vm.$bvToast, 'toast');

    await flushPromises();

    wrapper.vm.params.limit = 999;
    wrapper.vm.getData();

    await flushPromises();

    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('get visible columns', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    expect(wrapper.vm.getVisibleColumns().length).toBe(6);
  });

  it('resolve link path', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const obj: any = {
      id: 10,
      name: 'John Doe',
      role: {
        id: 1,
        name: 'admin',
        permissions: {
          runTests: true
        }
      }
    };

    expect(wrapper.vm.resolveLinkPath('/users/{id}', obj)).toBe('/users/10');
    expect(wrapper.vm.resolveLinkPath('/user-roles/{role.id}', obj)).toBe('/user-roles/1');
  });

  it('resolve path', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const obj: any = {
      id: 1,
      name: 'John Doe',
      address: {
        city: 'frankfurt',
        country: 'germany'
      },
      role: {
        name: 'admin',
        permissions: {
          runTests: true
        }
      }
    };

    expect(wrapper.vm.resolvePath('id', obj)).toBe(1);
    expect(wrapper.vm.resolvePath('address.city', obj)).toBe('frankfurt');
    expect(wrapper.vm.resolvePath('role.permissions.runTests', obj)).toBeTruthy();
  });

  it('clear all filters', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    wrapper.vm.params['filter[id]'] = 1;
    wrapper.vm.params['filter[name]'] = 'John Doe';
    expect(wrapper.vm.params)
      .toEqual({ limit: 50, page: 1, sort: '', 'filter[id]': 1, 'filter[name]': 'John Doe' });

    wrapper.vm.clearParamFilters();

    expect(wrapper.vm.params).toEqual({ limit: 50, page: 1, sort: '' });
  });

  it('deserialize the applied filters', async () => {
    const wrapper: any = factory(
      { columns: columns, filterConfig: filterConfig, historyMode: true, service: service, tableId: tableId }
    );

    await flushPromises();

    wrapper.vm.$router.currentRoute.query =
      { limit: 10, page: 2, sort: '', 'filter[id]': '1', 'filter[name]': 'John Doe' };

    expect(wrapper.vm.filters).toEqual(filterConfig);

    wrapper.vm.deserializeFilters();

    filterConfig[1].exact!.value = 'John Doe';
    filterConfig[2].clearValues();
    filterConfig[3].clearValues();
    filterConfig[4].clearValues();
    filterConfig[5].clearValues();
    expect(wrapper.vm.filters).toEqual(filterConfig);
  });

  it('reset filters to default when deserializing filters without any in the route query', async () => {
    const wrapper: any = factory(
      { columns: columns, filterConfig: filterConfig, historyMode: true, service: service, tableId: tableId }
    );

    await flushPromises();

    wrapper.vm.filters[0].clearValues();
    wrapper.vm.filters[3].clearValues();
    wrapper.vm.filters[4].clearValues();
    wrapper.vm.filters[5].clearValues();

    expect(wrapper.vm.filters).not.toEqual(filterConfig);

    wrapper.vm.deserializeFilters();

    expect(wrapper.vm.filters).toEqual(filterConfig);
  });

  it('have active filters', () => {
    const wrapper: any = factory(
      { columns: columns, filterConfig: filterConfig, service: service, tableId: tableId }
    );

    expect(wrapper.vm.hasActiveFilter()).toBeTruthy();
  });

  it('not have active filters', () => {
    const wrapper: any = factory(
      { columns: columns, service: service, tableId: tableId }
    );

    expect(wrapper.vm.hasActiveFilter()).toBeFalsy();
  });

  it('serialize filters', () => {
    const wrapper: any = factory(
      { columns: columns, filterConfig: filterConfig, service: service, tableId: tableId }
    );

    wrapper.vm.serializeFilters();

    expect(wrapper.vm.params).toEqual({
      limit: 50,
      page: 1,
      sort: '',
      'filter[age_from]': '10',
      'filter[age_to]': '20',
      'filter[dob_from]': '1970-01-01',
      'filter[email]': 'exotelis@mailbox.org',
      'filter[id]': '1',
      'filter[size_to]': '180'
    });
  });

  it('determine the correct sort icon', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    wrapper.vm.params.sort = 'name';
    expect(wrapper.vm.determineSortIcon('id')).toBe(wrapper.vm.sortIcons[0]);

    wrapper.vm.params.sort = 'name,id';
    expect(wrapper.vm.determineSortIcon('id')).toBe(wrapper.vm.sortIcons[1]);

    wrapper.vm.params.sort = 'name,-id,age';
    expect(wrapper.vm.determineSortIcon('id')).toBe(wrapper.vm.sortIcons[2]);
  });

  it('handle single sort', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    wrapper.vm.sortMode = SortMode.single;

    wrapper.vm.handleSort('id');
    expect(wrapper.vm.params.sort).toBe('id');

    wrapper.vm.handleSort('id');
    expect(wrapper.vm.params.sort).toBe('-id');

    wrapper.vm.handleSort('name');
    expect(wrapper.vm.params.sort).toBe('name');

    wrapper.vm.handleSort('name');
    expect(wrapper.vm.params.sort).toBe('-name');

    wrapper.vm.handleSort('name');
    expect(wrapper.vm.params.sort).toBe('');
  });

  it('handle multi sort', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    wrapper.vm.sortMode = SortMode.multi;

    wrapper.vm.handleSort('id');
    expect(wrapper.vm.params.sort).toBe('id');

    wrapper.vm.handleSort('name');
    expect(wrapper.vm.params.sort).toBe('id,name');

    wrapper.vm.handleSort('id');
    expect(wrapper.vm.params.sort).toBe('-id,name');

    wrapper.vm.handleSort('id');
    expect(wrapper.vm.params.sort).toBe('name');
  });

  it('get the correct colspan', () => {
    const actions: Array<DatatableActionModel> = [
      { label: 'Show', eventId: 'show', icon: 'mdi-eye' }
    ];
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const actionsWrapper: any = factory(
      { actions: actions, columns: columns, service: service, tableId: tableId }
    );
    const selectableWrapper: any = factory(
      { columns: columns, selectable: true, service: service, tableId: tableId }
    );
    const actionsSelectableWrapper: any = factory(
      { actions: actions, columns: columns, selectable: true, service: service, tableId: tableId }
    );

    expect(wrapper.vm.getColspan).toBe(6);
    expect(actionsWrapper.vm.getColspan).toBe(7);
    expect(selectableWrapper.vm.getColspan).toBe(7);
    expect(actionsSelectableWrapper.vm.getColspan).toBe(8);
  });

  it('not select any element if no record has been found', async () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });

    await flushPromises();

    wrapper.vm.response.data = null;

    expect(wrapper.vm.selectAll).toBeFalsy();
  });

  it('return false when getting \'selectAll\' if no elements are selected', async () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });

    await flushPromises();

    expect(wrapper.vm.selectAll).toBeFalsy();
    expect(wrapper.find('#datatable_selectAll').element.checked).toBeFalsy();
    expect(wrapper.find('#datatable_selectAll:indeterminate').exists()).toBeFalsy();
  });

  it('return false when getting \'selectAll\' if just some elements are selected', async () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });

    await flushPromises();

    wrapper.find('#datatable_select-0').setChecked();

    expect(wrapper.vm.selectAll).toBeFalsy();
    expect(wrapper.find('#datatable_selectAll').element.checked).toBeFalsy();
    expect(wrapper.find('#datatable_selectAll:indeterminate').exists()).toBeTruthy();
  });

  it('return true when getting \'selectAll\' if all elements on a page are selected', async () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });

    await flushPromises();

    wrapper.findAll('#datatable_selectAll').setChecked();

    expect(wrapper.vm.selectAll).toBeTruthy();
    expect(wrapper.find('#datatable_selectAll').element.checked).toBeTruthy();
    expect(wrapper.find('#datatable_selectAll:indeterminate').exists()).toBeFalsy();
    expect(wrapper.vm.selection.length).toBe(wrapper.vm.response.data.length);
  });

  it('not select all rows if no records have been found', async () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });

    await flushPromises();

    wrapper.vm.response.data = null;
    wrapper.vm.selectAll = true;

    expect(wrapper.vm.selection).toEqual([]);
  });

  it('select and deselect all rows', async () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });

    await flushPromises();

    wrapper.vm.selectAll = true;
    expect(wrapper.vm.selection).toEqual(wrapper.vm.response.data);

    wrapper.vm.selectAll = false;
    expect(wrapper.vm.selection).toEqual([]);
  });

  it('fix $route.query types', () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });
    const defaultParams: any = { limit: 50, page: 1, sort: 'id' };

    expect(wrapper.vm.fixQueryTypes({ limit: '50', page: '1', sort: 'id' })).toEqual(defaultParams);
    expect(wrapper.vm.fixQueryTypes({ page: '1', sort: 'id' })).toEqual({ page: 1, sort: 'id' });
    expect(wrapper.vm.fixQueryTypes({ limit: '50', sort: 'id' })).toEqual({ limit: 50, sort: 'id' });
  });

  /**
   * Event listener tests
   */

  it('update \'visibleColumns\' when event is caught', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const visibleColumns: Array<string> = ['id', 'email'];

    wrapper.vm.onColumnSelectionChange(visibleColumns);
    expect(wrapper.vm.visibleColumns).toEqual(visibleColumns);
  });

  it('update \'density\' when event is caught', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const density: Density = Density.s;

    wrapper.vm.onDensityChange(density);
    expect(wrapper.vm.density).toEqual(density);
  });

  it('update \'filters\' and call serializeFilters when event is caught', () => {
    const serializeFilters = jest.fn();
    const wrapper: any = factory(
      { columns: columns, service: service, tableId: tableId },
      {},
      { serializeFilters: serializeFilters }
    );
    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' })
    ];

    wrapper.vm.onFiltersChanged(filters);
    expect(wrapper.vm.filters).toEqual(filters);
    expect(serializeFilters).toHaveBeenCalled();
  });

  it('update \'params.page\' when event is caught', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const page: number = 15;

    wrapper.vm.onPageChange(page);
    expect(wrapper.vm.params.page).toEqual(page);
  });

  it('update \'filtersVisible\' when event is caught', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const visible: boolean = true;

    wrapper.vm.onFiltersToggle(visible);
    expect(wrapper.vm.filtersVisible).toEqual(visible);
  });

  it('call \'getData\' when refresh event is caught', () => {
    const getData = jest.fn();
    const wrapper: any = factory(
      { columns: columns, service: service, tableId: tableId },
      {},
      { getData: getData }
    );

    wrapper.vm.onRefresh();
    expect(getData).toHaveBeenCalled();
  });

  it('update \'params.limit\' when event is caught', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const limit: number = 75;

    wrapper.vm.onRowsPerPageChange(limit);
    expect(wrapper.vm.params.limit).toEqual(limit);
  });

  it('update \'sortMode\' when event is caught', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const mode: SortMode = SortMode.single;

    wrapper.vm.onSortModeChange(mode);
    expect(wrapper.vm.sortMode).toEqual(mode);
  });

  it('trigger checkbox even if the checkbox wasn\'t clicked but the table cell', async () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });
    const spy = jest.spyOn(wrapper.vm, 'triggerCheckbox');

    await flushPromises();

    const element: any = wrapper.find('tbody tr .datatable_selection');
    element.trigger('click');

    expect(spy).toHaveBeenCalledTimes(1);
    expect(wrapper.vm.selection.length).toBe(1);

    element.trigger('click');
    expect(spy).toHaveBeenCalledTimes(2);
    expect(wrapper.vm.selection.length).toBe(0);

    spy.mockRestore();
  });

  /**
   * Watcher
   */

  it('check if watcher \'queryChange\' will call \'deserializeFilters\' and fix query types', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const spy = jest.spyOn(wrapper.vm, 'deserializeFilters');

    wrapper.vm.queryChange({ limit: '10', page: '5', sort: '' });

    expect(spy).toHaveBeenCalled();
    expect(wrapper.vm.params).toEqual({ limit: 10, page: 5, sort: '' });

    spy.mockRestore();
  });

  it('check if watcher \'onFilterConfigChange\' update filters', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    const spy = jest.spyOn(wrapper.vm, 'deserializeFilters');

    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter(
        'TestNew',
        { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' })
    ];

    wrapper.vm.onFilterConfigChange(filters);

    expect(spy).not.toHaveBeenCalled();
    expect(wrapper.vm.filters).toEqual(filters);

    spy.mockRestore();
  });

  it('check if watcher \'onFilterConfigChange\' will call \'deserializeFilters\' in history mode', () => {
    const wrapper: any = factory(
      { columns: columns, historyMode: true, service: service, tableId: tableId },
      { 'filter[testProp]': 'testValue' }
    );
    const spy = jest.spyOn(wrapper.vm, 'deserializeFilters');

    const filters: Array<DatatableBaseFilter> = [
      new DatatableExactFilter(
        'TestNew',
        { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' })
    ];

    wrapper.vm.onFilterConfigChange(filters);

    expect(spy).toHaveBeenCalled();
    expect(wrapper.vm.filters).toEqual(filters);

    spy.mockRestore();
  });

  it('push to user to another page when history mode is enabled, the params are not default and the params' +
    'have been changed', () => {
    const wrapper: any = factory({ columns: columns, historyMode: true, service: service, tableId: tableId });
    const spy = jest.spyOn(wrapper.vm.$router, 'push');

    wrapper.vm.onParamsChange({ limit: 10, page: 1, sort: '' });

    expect(spy).toHaveBeenCalled();
    // expect(wrapper.vm.params).toBe({ limit: 10, page: 5, sort: '' });

    spy.mockRestore();
  });

  it('restore params defaults', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    wrapper.vm.params = {};
    wrapper.vm.onParamsChange({});

    expect(wrapper.vm.params).toEqual({ limit: 50, page: 1, sort: '' });
  });

  it('call \'getData\' on params change', async () => {
    const getData = jest.fn();
    const wrapper: any = factory(
      { columns: columns, service: service, tableId: tableId },
      {},
      { getData: getData }
    );

    await flushPromises();

    wrapper.vm.onParamsChange({ limit: 10, page: 2, sort: 'id' });

    expect(getData).toHaveBeenCalled();
  });

  it('emit event on selection change', async () => {
    const wrapper: any = factory({ columns: columns, selectable: true, service: service, tableId: tableId });

    await flushPromises();

    const selectedRow: Array<object> = [wrapper.vm.response.data[0]];
    wrapper.vm.onSelectionChange(selectedRow);

    expect(wrapper.emitted('datatable:selection')).toBeTruthy();
    expect(wrapper.emitted('datatable:selection').length).toBe(2);
    expect(wrapper.emitted('datatable:selection')[1]).toEqual([selectedRow]);
  });

  /**
   * Column settings related tests
   */

  it('display label of column \'ID\' in thead', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    const idColumn = wrapper.findAll('thead .datatable_column-labels th').at(0);
    expect(idColumn.find('span').text()).toBe('ID');
  });

  it('set prop related class on col', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    expect(wrapper.findAll('colgroup col').at(0).classes()).toContain('datatable_col-id');
  });

  it('center \'First name\' column in thead', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    expect(wrapper.findAll('thead .datatable_column-labels th').at(2).classes()).toContain('text-center');
  });

  it('right align \'Last name\' column in thead', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    expect(wrapper.findAll('thead .datatable_column-labels th').at(3).classes()).toContain('text-right');
  });

  it('center \'First name\' column in tbody', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    await flushPromises();

    expect(wrapper.findAll('tbody tr td').at(2).classes()).toContain('text-center');
  });

  it('right align \'Last name\' column in tbody', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    await flushPromises();

    expect(wrapper.findAll('tbody tr td').at(3).classes()).toContain('text-right');
  });

  it('add class \'user-select-all\' to \'email\' column in tbody', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    await flushPromises();

    expect(wrapper.findAll('tbody tr td').at(1).classes()).toContain('user-select-all');
  });

  it('hide column \'Middle name\' by default', () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    expect(wrapper.findAll('thead .datatable_column-labels th').length).toBe(6);
  });

  /**
   * Template related tests
   */

  it('set density to small', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    wrapper.vm.density = Density.s;

    await wrapper.vm.$nextTick();

    expect(wrapper.find('table').classes()).toContain('table-sm');
  });

  it('set density to medium', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    expect(wrapper.find('table').classes()).toContain('table-md');
  });

  it('set density to large', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });
    wrapper.vm.density = Density.l;

    await wrapper.vm.$nextTick();

    const table: any = wrapper.find('table');
    expect(table.classes()).not.toContain('table-md');
    expect(table.classes()).not.toContain('table-sm');
  });

  it('display loading bar', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    await flushPromises();
    wrapper.vm.loading = true;

    expect(wrapper.find('.datatable_loading-bar .loading-bar').exists()).toBeTruthy();
    expect(wrapper.find('.datatable_loading-bar .loading-bar').classes()).not.toContain('hidden');
  });

  it('hide loading bar', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    await flushPromises();

    expect(wrapper.find('.datatable_loading-bar .loading-bar').exists()).toBeFalsy();
  });

  it('display loading message', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    await flushPromises();
    wrapper.vm.loading = true;
    wrapper.vm.response.data = null;

    expect(wrapper.find('tbody tr td div').text()).toBe('component.datatable.loading');
  });

  it('display \'no records found\' message, with \'filter combination\' hint', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    await flushPromises();
    wrapper.vm.filters = [
      new DatatableExactFilter(
        'Test',
        { inputType: FilterInputTypes.text, prop: 'testProp', value: 'testValue' })
    ];
    wrapper.vm.response.data = null;

    expect(wrapper.find('tbody .datatable_no-results').exists()).toBeTruthy();
    expect(wrapper.find('tbody .datatable_no-records-found').text()).toBe('component.datatable.noRecordsFound');
    expect(wrapper.find('tbody .datatable_no-records-filter-hint span').text())
      .toBe('component.datatable.filterCombination');
    expect(wrapper.find('tbody .datatable_no-records-filter-hint a').text())
      .toBe('component.datatable.clearFilters');
  });

  it('display \'no records found\' message, with \'add item\' hint', async () => {
    const wrapper: any = factory({ columns: columns, service: service, tableId: tableId });

    await flushPromises();
    wrapper.vm.response.data = null;

    expect(wrapper.find('tbody .datatable_no-results').exists()).toBeTruthy();
    expect(wrapper.find('tbody .datatable_no-records-found').text()).toBe('component.datatable.noRecordsFound');
    expect(wrapper.find('tbody .datatable_no-records-add-item-hint').text())
      .toBe('component.datatable.addItem');
  });
});
