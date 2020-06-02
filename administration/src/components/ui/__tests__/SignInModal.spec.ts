import { config, mount } from '@vue/test-utils';
import { EventBus } from '@/event-bus';
import flushPromises from 'flush-promises';
import SignInModal from '../SignInModal.vue';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

describe('SignInModal.vue', () => {
  let component: any;

  beforeEach(() => {
    localStorage.setItem('user', '{ "firstname": "John" }');
    component = mount(SignInModal, {
      stubs: ['b-modal']
    });
  });

  afterEach(() => {
    localStorage.removeItem('user');
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check if disabledSubmit is false by default', () => {
    expect(component.vm.disabledSubmit).toBeTruthy();
  });

  it('check if error is null by default', () => {
    expect(component.vm.error).toBe(null);
  });

  it('check if firstname is empty by default', () => {
    expect(component.vm.firstname).toBe('');
  });

  it('check if loading is false by default', () => {
    expect(component.vm.firstname).toBeFalsy();
  });

  it('check if is enabeld when field password isn\'t empty', async () => {
    EventBus.$emit('sign-in-modal');
    await component.vm.$nextTick();
    component.find('#sign-in-password').setValue('admin');
    expect(component.vm.disabledSubmit).toBeFalsy();
  });

  it('set the firstname', () => {
    component.vm.handleShow();
    expect(component.vm.firstname).toBe('John');
  });

  it('handle the submit when remain sign in button is clicked', async () => {
    const spy = jest.spyOn(component.vm, 'handleLogin');

    EventBus.$emit('sign-in-modal');
    await component.vm.$nextTick();
    component.find('#sign-in-password').setValue('admin');
    component.find('form').trigger('submit');

    expect(spy).toHaveBeenCalled();

    await flushPromises();
    expect(component.vm.error).toBe('Something went wrong.');
  });

  it('remain signed in', async () => {
    const spy = jest.spyOn(component.vm.$bvModal, 'hide');
    localStorage.setItem('user', '{ "firstname": "John", "username": "admin" }');
    EventBus.$emit('sign-in-modal');
    await component.vm.$nextTick();
    component.find('#sign-in-password').setValue('admin');
    component.find('form').trigger('submit');

    await flushPromises();
    expect(spy).toHaveBeenCalledWith('sign-in-modal');
  });
});
