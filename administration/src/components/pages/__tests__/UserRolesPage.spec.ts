import { config, shallowMount } from '@vue/test-utils';
import { ActionMode } from '@/enum/ActionMode';
import { EventBus } from '@/event-bus';
import * as helpers from '@/helpers';
import RoleModel from '@/models/RoleModel';
import UserRoleService from '@/services/UserRoleService';
import UserRolesPage from '@/components/pages/UserRolesPage.vue';

jest.mock('@/services/UserRoleService');

config.mocks!.$t = (key: any) => key;
config.mocks!.$tc = (key: any) => key;

const factory = () => {
  return shallowMount(UserRolesPage, {
    mocks: {
      $router: {
        push: (params: any) => params
      }
    },
    stubs: ['router-link']
  });
};

describe('UserRolesPage.vue', () => {
  let roles: Array<RoleModel> = [];

  beforeEach(() => {
    roles = [
      { 'id': 1, 'color': '#1d2530', 'deletable': true, 'editable': true, 'name': 'Administrator', 'created_at': '2020-07-05T12:00:00.000000Z', 'updated_at': '2020-07-05T12:00:00.000000Z' },
      { 'id': 2, 'color': '#6c757d', 'deletable': true, 'editable': true, 'name': 'User', 'created_at': '2020-07-05T12:00:00.000000Z', 'updated_at': '2020-07-05T12:00:00.000000Z' },
      { 'id': 3, 'color': '#ed7c3b', 'deletable': true, 'editable': true, 'name': 'Moderator', 'created_at': '2020-07-05T12:00:00.000000Z', 'updated_at': '2020-07-05T12:00:00.000000Z' }
    ];
  });

  it('is Vue instance', () => {
    const wrapper: any = factory();
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('not show any action', () => {
    const spy = jest.spyOn(helpers, 'checkPermissions').mockImplementation(() => false);
    const wrapper: any = factory();
    expect(wrapper.vm.actions).toStrictEqual([]);
    expect(wrapper.vm.bulkActions).toStrictEqual([]);

    spy.mockRestore();
  });

  it('show edit and delete actions', () => {
    const spy = jest.spyOn(helpers, 'checkPermissions').mockImplementation(() => true);
    const wrapper: any = factory();
    expect(wrapper.vm.actions).toContainObject({ label: 'general.delete' });
    expect(wrapper.vm.actions).toContainObject({ label: 'general.edit' });
    expect(wrapper.vm.bulkActions).toContainObject({ label: 'general.delete' });

    spy.mockRestore();
  });

  it('show delete modal dialog', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm, 'deleteModal').mockImplementation(() => false);
    const spyDelete = jest.spyOn(UserRoleService, 'bulkDelete');
    const spyToast = jest.spyOn(wrapper.vm.$bvToast, 'toast');

    await wrapper.vm.deleteRoles(roles[0], true, ActionMode.single);

    expect(spy).toHaveBeenCalledWith(
      'page.userRoles.deleteModalTitle',
      'page.userRoles.deleteModalText',
      'page.userRoles.deleteModalOk'
    );
    expect(spyDelete).not.toHaveBeenCalled();
    expect(spyToast).not.toHaveBeenCalled();

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('delete a single user role', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm, 'deleteModal').mockImplementation(() => true);
    const spyDelete = jest.spyOn(UserRoleService, 'bulkDelete');
    const spyEvent = jest.spyOn(EventBus, '$emit');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.deleteRoles(roles[0], true, ActionMode.single);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyEvent).toHaveBeenCalledWith('datatable:refresh');
    expect(spyToast).toHaveBeenCalledWith(
      'page.userRoles.deletedTitle',
      'The user role has been deleted.',
      'success'
    );

    spy.mockRestore();
    spyDelete.mockRestore();
    spyEvent.mockRestore();
    spyToast.mockRestore();
  });

  it('delete multiple user roles', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm, 'deleteModal').mockImplementation(() => true);
    const spyDelete = jest.spyOn(UserRoleService, 'bulkDelete');
    const spyEvent = jest.spyOn(EventBus, '$emit');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.deleteRoles(roles, true, ActionMode.bulk);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyEvent).toHaveBeenCalledWith('datatable:refresh');
    expect(spyToast).toHaveBeenCalledWith(
      'page.userRoles.deletedTitle',
      roles.length + ' user roles have been deleted.',
      'success'
    );
    spy.mockRestore();
    spyDelete.mockRestore();
    spyEvent.mockRestore();
    spyToast.mockRestore();
  });

  it('fail to delete a user role', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm, 'deleteModal').mockImplementation(() => true);
    const spyDelete = jest.spyOn(UserRoleService, 'bulkDelete');
    const spyToast = jest.spyOn(wrapper.vm.$bvToast, 'toast');

    roles[0].id = 999; // Mock fail - 422
    await wrapper.vm.deleteRoles(roles[0], true, ActionMode.single);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyToast).toHaveBeenLastCalledWith(
      expect.objectContaining({
        data: { staticClass: 422 }
      }),
      expect.objectContaining({
        title: 'page.userRoles.deletedTitleError'
      })
    );

    roles[0].id = 1000; // Mock fail - 403
    await wrapper.vm.deleteRoles(roles[0], true, ActionMode.single);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyToast).toHaveBeenLastCalledWith(
      expect.objectContaining({
        data: { staticClass: 403 }
      }),
      expect.objectContaining({
        title: 'page.userRoles.deletedTitleError'
      })
    );

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('redirect to edit role page', () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm.$router, 'push');

    wrapper.vm.editRole(roles[0]);
    expect(spy).toHaveBeenCalledWith({ 'path': '/user-roles/' + roles[0].id });

    spy.mockRestore();
  });
});
