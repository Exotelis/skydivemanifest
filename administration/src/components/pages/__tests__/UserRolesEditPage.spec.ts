import { config, mount } from '@vue/test-utils';
import UserRolesEditPage from '@/components/pages/UserRolesEditPage.vue';
import UserRoleService from '@/services/UserRoleService';

jest.mock('@/services/UserRoleService');

config.mocks!.$t = (key: any) => key;
config.mocks!.$tc = (key: any) => key;

describe('UserRolesEditPage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = mount(UserRolesEditPage, {
      mocks: {
        $route: {
          params: { id: 1 }
        },
        $router: {
          go: (step: number) => step,
          push: (config: object) => config
        }
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('have an id assigned', () => {
    expect(component.vm.id).toBe(1);
  });

  it('have the correct service', () => {
    expect(component.vm.service).toBe(UserRoleService.update);
  });

  it('have a success title and message', () => {
    expect(component.vm.successMessage).toBe('page.userRoles.updatedMessage');
    expect(component.vm.successTitle).toBe('page.userRoles.updatedTitle');
  });

  it('get role information', async () => {
    const spyService = jest.spyOn(UserRoleService, 'get');

    component.vm.id = 1;
    component.vm.roleLoaded = false;

    expect(component.vm.roleLoaded).toBeFalsy();

    await component.vm.getRole();

    expect(spyService).toHaveBeenCalled();
    expect(component.vm.form).toStrictEqual({
      'color': '#1d2530',
      'deletable': false,
      'editable': false,
      'id': 1,
      'name': 'Administrator',
      'permissions': ['personal']
    });
    expect(component.vm.roleLoaded).toBeTruthy();

    spyService.mockRestore();
  });

  it('fail to get role information', async () => {
    await component.vm.$nextTick(); // Wait for created to be finished
    const spyRouter = jest.spyOn(component.vm.$router, 'go');
    const spyService = jest.spyOn(UserRoleService, 'get');
    const spyToast = jest.spyOn(component.vm, 'toast');

    component.vm.id = 2;
    component.vm.roleLoaded = false;

    expect(component.vm.roleLoaded).toBeFalsy();

    await component.vm.getRole();

    expect(spyService).toHaveBeenCalled();
    expect(spyRouter).toHaveBeenCalledWith(-1);
    expect(spyToast).toHaveBeenCalledWith(
      'page.userRoles.couldNotLoadRole',
      'The requested resource was not found.',
      'danger'
    );
    expect(component.vm.roleLoaded).toBeFalsy();

    spyRouter.mockRestore();
    spyService.mockRestore();
    spyToast.mockRestore();
  });

  it('stop role deletion if user canceled it', async () => {
    const spy = jest.spyOn(component.vm, 'deleteModal').mockImplementationOnce(() => false);
    const spyService = jest.spyOn(UserRoleService, 'delete');

    await component.vm.handleDelete();

    expect(spy).toHaveBeenCalledWith(
      'page.userRoles.deleteModalTitle',
      'page.userRoles.deleteModalText',
      'page.userRoles.deleteModalOk'
    );
    expect(spyService).not.toHaveBeenCalled();

    spy.mockRestore();
    spyService.mockRestore();
  });

  it('delete the user role', async () => {
    component.vm.deleteModal = jest.fn().mockReturnValue(true);
    const spyRouter = jest.spyOn(component.vm.$router, 'push');
    const spyService = jest.spyOn(UserRoleService, 'delete');
    const spyToast = jest.spyOn(component.vm, 'toast');

    component.vm.id = 1;
    await component.vm.handleDelete();

    expect(spyService).toHaveBeenCalled();
    expect(spyRouter).toHaveBeenCalledWith({ path: '/user-roles' });
    expect(spyToast).toHaveBeenCalledWith(
      'page.userRoles.deletedTitle',
      'The user role has been deleted successfully.',
      'success'
    );

    spyRouter.mockRestore();
    spyService.mockRestore();
    spyToast.mockRestore();
  });

  it('fail to delete the user role', async () => {
    component.vm.deleteModal = jest.fn().mockReturnValue(true);
    const spyToast = jest.spyOn(component.vm, 'toast');

    component.vm.id = 10;
    await component.vm.handleDelete();

    expect(spyToast).toHaveBeenCalledWith('page.userRoles.deletedTitleError', 'The request failed.', 'danger');

    spyToast.mockRestore();
  });

  it('trigger handleSubmit of the form sub component', async () => {
    component.vm.roleLoaded = true;
    const spy = jest.spyOn(component.vm.$refs.userRoleForm, 'handleSubmit');
    const submitButton: any = component.find('#updateRoleButton');

    submitButton.trigger('click');

    await component.vm.$nextTick();

    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('update the loading state on submit', () => {
    expect(component.vm.loadingEdit).toBeFalsy();
    component.vm.updateLoading(true);
    expect(component.vm.loadingEdit).toBeTruthy();
    component.vm.updateLoading(false);
    expect(component.vm.loadingEdit).toBeFalsy();
  });
});
