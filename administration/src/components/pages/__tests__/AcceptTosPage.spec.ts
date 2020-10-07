import { config, mount } from '@vue/test-utils';
import AcceptTosPage from '../AcceptTosPage.vue';
import AuthService from '@/services/AuthService';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

describe('AcceptTosPage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = mount(AcceptTosPage, {
      stubs: ['i18n', 'router-link']
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check if submit button is disabled checkbox is not checked', () => {
    expect(component.find('button').attributes().disabled).toBe('disabled');
    component.find('#tos').trigger('click');
    expect(component.find('button').attributes().disabled).toBeUndefined();
  });

  it('check if method handleSubmit gets called', async () => {
    const spy = jest.spyOn(component.vm, 'handleSubmit');

    component.find('#tos').trigger('click');

    component.find('form').trigger('submit');
    await component.vm.$nextTick();
    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('check if submit was stopped because of validation errors', async () => {
    component.vm.errors = { tos: 'Something went wrong.' };

    await component.vm.handleSubmit();
    expect(component.vm.hasValidationError()).toBeTruthy();
    expect(component.emitted().changeRoute).toBeFalsy();
    expect(component.vm.error).toBeNull();
  });

  it('check if terms of service have been accepted and the token could be refreshed', async () => {
    const spy = jest.spyOn(AuthService, 'refresh');

    component.vm.tos = [true];

    await component.vm.handleSubmit();
    expect(spy).toHaveBeenCalled();
    expect(component.vm.error).toBeNull();
    expect(component.vm.successMessage).toBe('The Terms of Service have been accepted.');

    spy.mockRestore();
  });

  it('check if terms of service have been accepted and the user has been logged out', async () => {
    Object.defineProperty(AuthService, 'refreshToken', {
      value: null
    });
    const spy = jest.spyOn(AuthService, 'logout');

    component.vm.tos = [true];

    await component.vm.handleSubmit();
    expect(spy).toHaveBeenCalled();
    expect(component.vm.error).toBeNull();
    expect(component.vm.successMessage).toBe('The Terms of Service have been accepted.');

    spy.mockRestore();
  });

  it('check if accepting the terms of service failed', async () => {
    component.vm.tos = [false];

    await component.vm.handleSubmit();
    expect(component.vm.successMessage).toBeNull();
    expect(component.vm.error).toBe('Bad Request.');
  });
});
