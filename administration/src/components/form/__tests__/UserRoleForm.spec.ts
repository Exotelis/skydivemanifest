import { config, mount } from '@vue/test-utils';
import PermissionService from '@/services/PermissionService';
import UserRoleForm from '../UserRoleForm.vue';
import UserRoleService from '@/services/UserRoleService';

config.mocks!.$t = (key: any) => key;

jest.mock('@/services/PermissionService');
jest.mock('@/services/UserRoleService');

const factory = (props = {}) => {
  return mount(UserRoleForm, {
    mocks: {
      $router: {
        push: (route: any) => route
      }
    },
    propsData: {
      service: UserRoleService.add,
      successNotificationMessage: 'Success message',
      successNotificationTitle: 'Success title',
      ...props
    }
  });
};

describe('UserRoleForm.vue', () => {
  const permissions: any = {
    addresses: {
      delete: { 'slug': 'addresses:delete', 'is_default': false, 'name': 'Delete addresses', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
      read: { 'slug': 'addresses:read', 'is_default': false, 'name': 'Read addresses', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
      write: { 'slug': 'addresses:write', 'is_default': false, 'name': 'Add/Edit addresses', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' }
    },
    countries: {
      delete: { 'slug': 'countries:delete', 'is_default': false, 'name': 'Delete countries', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
      read: { 'slug': 'countries:read', 'is_default': true, 'name': 'Read countries', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
      write: { 'slug': 'countries:write', 'is_default': false, 'name': 'Add/Edit countries', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' }
    },
    permissions: {
      read: { 'slug': 'permissions:read', 'is_default': false, 'name': 'Read permissions', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' }
    },
    personal: {
      all: { 'slug': 'personal', 'is_default': true, 'name': 'Access to personal information', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' }
    },
    users: {
      delete: { 'slug': 'users:delete', 'is_default': true, 'name': 'Delete users', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
      read: { 'slug': 'users:read', 'is_default': true, 'name': 'Read users', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
      write: { 'slug': 'users:write', 'is_default': true, 'name': 'Add/Edit users', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' }
    }
  };

  it('is Vue instance', () => {
    const wrapper: any = factory({ formData: {} });
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('set initial form data', () => {
    let formData: any = { color: '#123456', name: 'test', permissions: [] };
    const wrapper: any = factory({ formData: formData });

    expect(wrapper.vm.form).toStrictEqual(formData);
  });

  it('load permissions', async () => {
    const wrapper: any = factory({ formData: { permissions: ['personal'] } });
    await wrapper.vm.$nextTick();

    expect(wrapper.vm.permissions).toStrictEqual(permissions);
  });

  it('emit validate event and return handleSubmit because of validation errors', async () => {
    const wrapper: any = factory({ formData: {} });

    await wrapper.vm.handleSubmit();

    expect(wrapper.emitted('validate')).toBeTruthy();
    expect(wrapper.emitted('form:loading')).toBeFalsy();
  });

  it('submit the form successfully', async () => {
    const wrapper: any = factory({ formData: { color: '#123456', name: 'Moderator' } });
    const spyPush = jest.spyOn(wrapper.vm.$router, 'push');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.handleSubmit();

    expect(wrapper.emitted('form:loading')).toBeTruthy();
    expect(spyPush).toHaveReturnedWith({ 'path': '/user-roles' });
    expect(spyToast).toHaveBeenCalledWith(
      'Success title',
      'Success message',
      'success'
    );

    spyPush.mockRestore();
    spyToast.mockRestore();
  });

  it('fail to submit the form', async () => {
    const wrapper: any = factory({ formData: { color: '#123456', id: 1, name: 'User' } });
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.handleSubmit();

    expect(spyToast).toHaveBeenCalledWith(
      'general.couldNotSave',
      'The request failed.',
      'danger'
    );

    spyToast.mockRestore();
  });

  it('fail to submit because of server side validation error', async () => {
    const wrapper: any = factory({ formData: { color: '#123456', name: 'Administrator' } });
    const spy = jest.spyOn(wrapper.vm, 'toast');
    const spyValidateResponse = jest.spyOn(wrapper.vm, 'validateResponse');

    await wrapper.vm.handleSubmit();

    expect(spyValidateResponse).toHaveBeenCalled();
    expect(spy).not.toHaveBeenCalled();

    spy.mockRestore();
    spyValidateResponse.mockRestore();
  });

  /**
   * Utilities
   */

  it('return the table element', () => {
    const wrapper: any = factory({ formData: {} });

    const table: HTMLTableElement = wrapper.find('#permissionsTable').element;
    expect(wrapper.vm.getTable()).toStrictEqual(table);
  });

  it('select a cell', async () => {
    const wrapper: any = factory({ formData: {} });
    await wrapper.vm.$nextTick();

    const table: HTMLTableElement = wrapper.find('#permissionsTable').element;
    const cell: HTMLTableCellElement = table.rows[1].cells[1];
    const input: HTMLInputElement = cell.getElementsByTagName('input')[0];

    expect(input?.checked).toBeFalsy();
    wrapper.vm.selectCell(cell);
    expect(input?.checked).toBeTruthy();
  });

  it('select a column', async () => {
    const wrapper: any = factory({ formData: {} });
    await wrapper.vm.$nextTick();
    const cell: number = 2;

    const table: HTMLTableElement = wrapper.find('#permissionsTable').element;

    // Gather inputs
    const inputs: Array<HTMLInputElement> = [];
    for (let i = 1; i < table.rows.length; i++) {
      if (table.rows[i].cells.length !== table.rows[0].cells.length) {
        continue;
      }

      const result: HTMLCollectionOf<HTMLInputElement> = table.rows[i].cells[cell].getElementsByTagName('input');
      if (result.length > 0) {
        inputs.push(result[0]);
      }
    }

    wrapper.vm.selectColumn(cell);

    expect(inputs.length).toBe(3);
    for (let key in inputs) {
      expect(inputs[key].checked).toBeTruthy();
    }
  });

  it('return without result from selectColumn when table was not found', async () => {
    const wrapper: any = factory({ formData: {} });
    const spy = jest.spyOn(wrapper.vm, 'getTable').mockImplementation(() => null);
    await wrapper.vm.$nextTick();
    const cell: number = 2;

    const table: HTMLTableElement = wrapper.find('#permissionsTable').element;

    // Gather inputs
    const inputs: Array<HTMLInputElement> = [];
    for (let i = 1; i < table.rows.length; i++) {
      if (table.rows[i].cells.length !== table.rows[0].cells.length) {
        continue;
      }

      const result: HTMLCollectionOf<HTMLInputElement> = table.rows[i].cells[cell].getElementsByTagName('input');
      if (result.length > 0) {
        inputs.push(result[0]);
      }
    }

    wrapper.vm.selectColumn(cell);

    expect(inputs[0].checked).toBeFalsy();

    spy.mockRestore();
  });

  it('select a row', async () => {
    const wrapper: any = factory({ formData: {} });
    await wrapper.vm.$nextTick();

    const table: HTMLTableElement = wrapper.find('#permissionsTable').element;
    const row: HTMLTableRowElement = table.rows[1];
    const inputs: Array<HTMLInputElement> = Array.from(row.getElementsByTagName('input'));

    wrapper.vm.selectRow(1);

    expect(inputs.length).toBe(3);
    for (let key in inputs) {
      expect(inputs[key].checked).toBeTruthy();
    }
  });

  it('return without result from selectRow when table was not found', async () => {
    const wrapper: any = factory({ formData: {} });
    const spy = jest.spyOn(wrapper.vm, 'getTable').mockImplementation(() => null);
    await wrapper.vm.$nextTick();

    const table: HTMLTableElement = wrapper.find('#permissionsTable').element;
    const row: HTMLTableRowElement = table.rows[1];
    const inputs: Array<HTMLInputElement> = Array.from(row.getElementsByTagName('input'));

    wrapper.vm.selectRow(1);

    for (let key in inputs) {
      expect(inputs[key].checked).toBeFalsy();
    }

    spy.mockRestore();
  });

  it('select all checkboxes first if some are already selected', async () => {
    const wrapper: any = factory({ formData: {} });
    await wrapper.vm.$nextTick();
    const rowNr: number = 1;

    const table: HTMLTableElement = wrapper.find('#permissionsTable').element;
    const row: HTMLTableRowElement = table.rows[rowNr];
    const inputs: Array<HTMLInputElement> = Array.from(row.getElementsByTagName('input'));

    expect(inputs.length).toBe(3);

    inputs[1].checked = true;
    expect(inputs[1].checked).toBeTruthy();

    wrapper.vm.selectRow(rowNr);

    for (let key in inputs) {
      expect(inputs[key].checked).toBeTruthy();
    }

    wrapper.vm.selectRow(rowNr);

    for (let key in inputs) {
      expect(inputs[key].checked).toBeFalsy();
    }
  });

  /**
   * Mocked Permissions
   */

  it('display a toast when permissions could not be loaded', async () => {
    const spy = jest.spyOn(PermissionService, 'all').mockImplementation(
      () => Promise.reject(new Error('Something went wrong'))
    );
    const wrapper: any = factory({ formData: {} });
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.$nextTick();

    expect(spyToast).toHaveBeenCalledWith(
      'page.userRoles.couldNotLoadPermissions',
      'Something went wrong',
      'danger'
    );

    spy.mockRestore();
    spyToast.mockRestore();
  });
});
