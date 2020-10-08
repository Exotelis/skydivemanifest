import { config, mount } from '@vue/test-utils';
import AuthService from '@/services/AuthService';
import PasswordChangePage from '../PasswordChangePage.vue';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

describe('PasswordChangePage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = mount(PasswordChangePage, {
      stubs: ['router-link']
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check if submit button is disabled when a required field is empty', () => {
    expect(component.find('button').attributes().disabled).toBe('disabled');
    component.find('#password').setValue('oldSecret');
    component.find('#new_password').setValue('secret');
    component.find('#new_password_confirmation').setValue(' ');
    expect(component.find('button').attributes().disabled).toBe('disabled');
    component.find('#new_password_confirmation').setValue('secret');
    expect(component.find('button').attributes().disabled).toBeUndefined();
  });

  it('check if method handleSubmit gets called', async () => {
    const spy = jest.spyOn(component.vm, 'handleSubmit');
    component.find('#password').setValue('oldSecret');
    component.find('#new_password').setValue('secret');
    component.find('#new_password_confirmation').setValue('secret');

    component.find('form').trigger('submit');
    await component.vm.$nextTick();
    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('check if submit was stopped because of validation errors', async () => {
    component.vm.errors = { password: 'Something went wrong.' };

    await component.vm.handleSubmit();
    expect(component.vm.hasValidationError()).toBeTruthy();
    expect(component.emitted().changeRoute).toBeFalsy();
    expect(component.vm.error).toBeNull();
  });

  it('check if password change was successful and the token could be refreshed', async () => {
    const spy = jest.spyOn(AuthService, 'refresh');

    component.vm.form = {
      password: 'secret',
      new_password: 'newsecret',
      new_password_confirmation: 'newsecret'
    };

    await component.vm.handleSubmit();
    expect(spy).toHaveBeenCalled();
    expect(component.vm.error).toBeNull();
    expect(component.vm.successMessage).toBe('Your password has been changed successfully.');

    spy.mockRestore();
  });

  it('check if password change was successful and the user has been logged out', async () => {
    Object.defineProperty(AuthService, 'refreshToken', {
      value: null
    });
    const spy = jest.spyOn(AuthService, 'logout');

    component.vm.form = {
      password: 'secret',
      new_password: 'newsecret',
      new_password_confirmation: 'newsecret'
    };

    await component.vm.handleSubmit();
    expect(spy).toHaveBeenCalled();
    expect(component.vm.error).toBeNull();
    expect(component.vm.successMessage).toBe('Your password has been changed successfully.');

    spy.mockRestore();
  });

  it('check if password change failed', async () => {
    component.vm.form = {
      password: 'oldsecret',
      new_password: 'newsecret',
      new_password_confirmation: 'newsecret'
    };

    await component.vm.handleSubmit();
    expect(component.vm.successMessage).toBeNull();
    expect(component.vm.error).toBe('This action is unauthorized.');
  });
});
