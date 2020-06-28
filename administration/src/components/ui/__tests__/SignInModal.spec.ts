import { config, mount } from '@vue/test-utils';
import { EventBus } from '@/event-bus';
import flushPromises from 'flush-promises';
import SignInModal from '../SignInModal.vue';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

describe('SignInModal.vue', () => {
  let component: any;

  beforeEach(() => {
    document.cookie = 'XSRF-TOKEN=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjp7ImZpcnN0bmFtZSI6IkpvaG4ifX0';
    component = mount(SignInModal, {
      stubs: ['b-modal']
    });
  });

  afterEach(() => {
    document.cookie = 'XSRF-TOKEN= ; expires = Thu, 01 Jan 1970 00:00:00 GMT';
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

  it('set the firstname and the email in the background', async () => {
    document.cookie = 'XSRF-TOKEN=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjp7ImVtYWlsIjoiZXhvdGVsaXNAbWFpbGJveC5vcmciLCJmaXJzdG5hbWUiOiJKb2huIn19';
    EventBus.$emit('sign-in-modal');
    await component.vm.$nextTick();
    expect(component.vm.firstname).toBe('John');
    expect(component.vm.form.username).toBe('exotelis@mailbox.org');
    expect(component.vm.form.password).toBe('');
    expect(component.vm.error).toBeNull();
  });

  it('handle the submit when remain sign in button is clicked', async () => {
    const spy = jest.spyOn(component.vm, 'handleSubmit');

    EventBus.$emit('sign-in-modal');
    await component.vm.$nextTick();
    component.find('#sign-in-password').setValue('admin');
    component.find('form').trigger('submit');

    expect(spy).toHaveBeenCalled();

    await flushPromises();
    expect(component.vm.error).toBe('Something went wrong.');

    spy.mockRestore();
  });

  it('remain signed in', async () => {
    const spy = jest.spyOn(component.vm.$bvModal, 'hide');
    document.cookie = 'XSRF-TOKEN=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjp7ImVtYWlsIjoiZXhvdGVsaXNAbWFpbGJveC5vcmciLCJmaXJzdG5hbWUiOiJKb2huIn19';
    EventBus.$emit('sign-in-modal');
    await component.vm.$nextTick();
    component.find('#sign-in-password').setValue('admin');
    component.find('form').trigger('submit');

    await flushPromises();
    expect(spy).toHaveBeenCalledWith('sign-in-modal');

    expect(component.vm.form.password).toBe('');
    expect(component.vm.error).toBeNull();

    spy.mockRestore();
  });
});
