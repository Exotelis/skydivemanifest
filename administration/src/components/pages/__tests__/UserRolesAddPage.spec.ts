import { config, mount } from '@vue/test-utils';
import UserRolesAddPage from '@/components/pages/UserRolesAddPage.vue';
import UserRoleService from '@/services/UserRoleService';

config.mocks!.$t = (key: any) => key;

describe('UserRolesAddPage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = mount(UserRolesAddPage);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('have the correct service', () => {
    expect(component.vm.service).toBe(UserRoleService.add);
  });

  it('have a success title and message', () => {
    expect(component.vm.successMessage).toBe('page.userRoles.createdMessage');
    expect(component.vm.successTitle).toBe('page.userRoles.createdTitle');
  });

  it('trigger handleSubmit of the form sub component', async () => {
    const spy = jest.spyOn(component.vm.$refs.userRoleForm, 'handleSubmit');
    const submitButton: any = component.find('#createRoleButton');

    submitButton.trigger('click');

    await component.vm.$nextTick();

    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('update the loading state', () => {
    expect(component.vm.loading).toBeFalsy();
    component.vm.updateLoading(true);
    expect(component.vm.loading).toBeTruthy();
    component.vm.updateLoading(false);
    expect(component.vm.loading).toBeFalsy();
  });
});
