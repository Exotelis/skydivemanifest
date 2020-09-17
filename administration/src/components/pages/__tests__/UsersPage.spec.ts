import { config, shallowMount } from '@vue/test-utils';
import { ActionMode } from '@/enum/ActionMode';
import { Gender } from '@/enum/Gender';
import * as helpers from '@/helpers';
import UserModel from '@/models/UserModel';
import UserService from '@/services/UserService';
import UsersPage from '../UsersPage.vue';

jest.mock('@/services/UserService');
jest.mock('@/services/UserRoleService');

config.mocks!.$t = (key: any) => key;
config.mocks!.$tc = (key: any) => key;

const factory = (currentRouteName = 'users') => {
  return shallowMount(UsersPage, {
    mocks: {
      $router: {
        currentRoute: {
          name: currentRouteName
        }
      }
    },
    stubs: ['router-link']
  });
};

describe('UsersPage.vue', () => {
  let users: Array<UserModel> = [];

  beforeEach(() => {
    users = [
      { 'id': 1, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': Gender.m, 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } },
      { 'id': 2, 'default_invoice': 1, 'default_shipping': 1, 'dob': '2001-03-12', 'email': 'exotelis@mailbox.org', 'email_verified_at': null, 'firstname': 'John', 'gender': Gender.m, 'is_active': true, 'last_logged_in': '2011-01-15T13:33:55.000000Z', 'lastname': 'Doe', 'locale': 'en', 'lock_expires': null, 'middlename': null, 'mobile': null, 'password_change': false, 'phone': '+8252486166415', 'username': 'exotelis', 'timezone': 'Europe/Berlin', 'created_at': '2020-07-05T11:58:05.000000Z', 'updated_at': '2020-07-05T11:58:06.000000Z', 'deleted_at': null, 'name': 'Doe, John', 'role': { 'id': 1, 'color': '#1d2530', 'deletable': false, 'editable': false, 'name': 'Administrator', 'created_at': '2020-07-05T11:47:33.000000Z', 'updated_at': '2020-07-05T11:47:33.000000Z' } }
    ];
  });

  it('is Vue instance', () => {
    const wrapper: any = factory();
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('set trashed to true', () => {
    const wrapper: any = factory('users-trashed');
    expect(wrapper.vm.trashed).toBeTruthy();
  });

  it('show delete/restore actions in users view', () => {
    const spy = jest.spyOn(helpers, 'checkPermissions').mockImplementation(() => true);
    const wrapper: any = factory();
    expect(wrapper.vm.actions).toContainObject({ label: 'general.delete' });
    expect(wrapper.vm.bulkActions).toContainObject({ label: 'general.delete' });

    spy.mockRestore();
  });

  it('show delete/restore actions in trashed view', () => {
    const spy = jest.spyOn(helpers, 'checkPermissions').mockImplementation(() => true);
    const wrapper: any = factory('users-trashed');
    expect(wrapper.vm.actions).toContainObject({ label: 'general.restore' });
    expect(wrapper.vm.actions).toContainObject({ label: 'general.deletePermanently' });
    expect(wrapper.vm.bulkActions).toContainObject({ label: 'general.restore' });
    expect(wrapper.vm.bulkActions).toContainObject({ label: 'general.deletePermanently' });

    spy.mockRestore();
  });

  it('show delete modal dialog in normal view', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'msgBoxConfirm').mockImplementation(() => false);
    const spyDelete = jest.spyOn(UserService, 'bulkDelete');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.deleteUsers(users[0], true, ActionMode.single);

    expect(spy).toHaveBeenCalledWith('page.users.deleteModalText', expect.objectContaining({
      cancelTitle: 'general.cancel',
      okTitle: 'page.users.deleteModalOk',
      title: 'page.users.deleteModalTitle'
    }));
    expect(spyDelete).not.toHaveBeenCalled();
    expect(spyToast).not.toHaveBeenCalled();

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('delete a single user in normal view', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'msgBoxConfirm').mockImplementation(() => true);
    const spyDelete = jest.spyOn(UserService, 'bulkDelete');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.deleteUsers(users[0], true, ActionMode.single);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyToast).toHaveBeenCalledWith('page.users.deletedTitle', 'One user has been deleted.', 'success');

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('delete multiple users in normal view', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'msgBoxConfirm').mockImplementation(() => true);
    const spyDelete = jest.spyOn(UserService, 'bulkDelete');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.deleteUsers(users, true, ActionMode.bulk);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyToast)
      .toHaveBeenCalledWith('page.users.deletedTitle', users.length + ' users have been deleted.', 'success');

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('fail to delete user in normal view', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'msgBoxConfirm').mockImplementation(() => true);
    const spyDelete = jest.spyOn(UserService, 'bulkDelete');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    users[0].id = 999; // Mock fail
    await wrapper.vm.deleteUsers(users[0], true, ActionMode.single);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyToast).toHaveBeenCalledWith('page.users.deletedTitleError', 'page.users.deletedError', 'danger');

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('show delete modal dialog in trashed view', async () => {
    const wrapper: any = factory('users-trashed');
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'msgBoxConfirm').mockImplementation(() => false);
    const spyDelete = jest.spyOn(UserService, 'deletePermanently');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.deleteUsers(users[0], false, ActionMode.single);

    expect(spy).toHaveBeenCalledWith('page.users.deleteModalTextPermanently', expect.objectContaining({
      cancelTitle: 'general.cancel',
      okTitle: 'page.users.deleteModalOkPermanently',
      title: 'page.users.deleteModalTitlePermanently'
    }));
    expect(spyDelete).not.toHaveBeenCalled();
    expect(spyToast).not.toHaveBeenCalled();

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('delete a single user in trashed view', async () => {
    const wrapper: any = factory('users-trashed');
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'msgBoxConfirm').mockImplementation(() => true);
    const spyDelete = jest.spyOn(UserService, 'deletePermanently');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.deleteUsers(users[0], true, ActionMode.single);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyToast)
      .toHaveBeenCalledWith('page.users.deletedTitle', 'One user has been deleted permanently.', 'success');

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('delete multiple users in trashed view', async () => {
    const wrapper: any = factory('users-trashed');
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'msgBoxConfirm').mockImplementation(() => true);
    const spyDelete = jest.spyOn(UserService, 'deletePermanently');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.deleteUsers(users, true, ActionMode.bulk);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyToast).toHaveBeenCalledWith(
      'page.users.deletedTitle',
      users.length + ' users have been deleted permanently.',
      'success'
    );

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('fail to delete user in trashed view', async () => {
    const wrapper: any = factory('users-trashed');
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'msgBoxConfirm').mockImplementation(() => true);
    const spyDelete = jest.spyOn(UserService, 'deletePermanently');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    users[0].id = 999; // Mock fail
    await wrapper.vm.deleteUsers(users[0], true, ActionMode.single);

    expect(spyDelete).toHaveBeenCalled();
    expect(spyToast).toHaveBeenCalledWith('page.users.deletedTitleError', 'page.users.deletedError', 'danger');

    spy.mockRestore();
    spyDelete.mockRestore();
    spyToast.mockRestore();
  });

  it('restore a single user', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(UserService, 'restore');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.restoreUsers(users[0], false, ActionMode.single);

    expect(spy).toHaveBeenCalled();
    expect(spyToast).toHaveBeenCalledWith('page.users.restoredTitle', 'One user has been restored.', 'success');

    spy.mockRestore();
    spyToast.mockRestore();
  });

  it('restore a multiple users', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(UserService, 'restore');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    await wrapper.vm.restoreUsers(users, false, ActionMode.bulk);

    expect(spy).toHaveBeenCalled();
    expect(spyToast)
      .toHaveBeenCalledWith('page.users.restoredTitle', users.length + ' users have been restored.', 'success');

    spy.mockRestore();
    spyToast.mockRestore();
  });

  it('fail to restore users', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(UserService, 'restore');
    const spyToast = jest.spyOn(wrapper.vm, 'toast');

    users[0].id = 999; // Mock fail
    await wrapper.vm.restoreUsers(users, false, ActionMode.bulk);

    expect(spy).toHaveBeenCalled();
    expect(spyToast)
      .toHaveBeenCalledWith('page.users.restoredTitleError', 'The given data was invalid.', 'danger');

    spy.mockRestore();
    spyToast.mockRestore();
  });
});
