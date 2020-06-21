import { mount, config } from '@vue/test-utils';
import PasswordForgotPage from '../PasswordForgotPage.vue';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

const factory = (values = {}) => {
  return mount(PasswordForgotPage, {
    mocks: {
      $route: {
        query: { ...values }
      }
    },
    stubs: ['router-link']
  });
};

describe('PasswordForgotPage.vue', () => {
  it('is Vue instance', () => {
    const wrapper: any = factory();
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check if email is predefined when the email url parameter is set', async () => {
    const wrapper: any = factory({ email: 'exotelis@mailbox.org' });
    expect(wrapper.vm.email).toBe('exotelis@mailbox.org');
    expect(wrapper.vm.emailPreFilled).toBeTruthy();
    await wrapper.vm.$nextTick();
    expect(wrapper.find('#email').attributes().readonly).toBeTruthy();
  });

  it('check if email is not predefined when the email url parameter is not set', async () => {
    const wrapper: any = factory();
    expect(wrapper.vm.email).toBe('');
    expect(wrapper.vm.emailPreFilled).toBeFalsy();
    await wrapper.vm.$nextTick();
    expect(wrapper.find('#email').attributes().readonly).toBeUndefined();
  });

  it('check if submit button is disabled when a required field is empty', () => {
    const wrapper: any = factory();
    expect(wrapper.find('button').attributes().disabled).toBe('disabled');
    wrapper.find('#email').setValue('exotelis@mailbox.org');
    expect(wrapper.find('button').attributes().disabled).toBeUndefined();
  });

  it('check if method handleSubmit gets called', async () => {
    const wrapper: any = factory({ email: 'exotelis@mailbox.org' });
    const spy = jest.spyOn(wrapper.vm, 'handleSubmit');

    wrapper.find('form').trigger('submit');
    await wrapper.vm.$nextTick();
    expect(spy).toHaveBeenCalled();
-
    spy.mockRestore();
  });

  it('check if submit was stopped because of validation errors', async () => {
    const wrapper: any = factory({ email: 'exotelis@mailbox.org' });
    wrapper.vm.errors = { email: 'Something went wrong.' };

    await wrapper.vm.handleSubmit();
    expect(wrapper.vm.hasValidationError()).toBeTruthy();
    expect(wrapper.vm.error).toBeNull();
  });

  it('check if password forgot was successful', async () => {
    const wrapper: any = factory({ email: 'exotelis@mailbox.org' });

    await wrapper.vm.handleSubmit();
    expect(wrapper.vm.error).toBeNull();
    expect(wrapper.vm.successMessage).toBe('We have emailed your password reset link!');
  });

  it('check if password forgot failed', async () => {
    const wrapper: any = factory({ email: 'test@example.com' });

    await wrapper.vm.handleSubmit();
    expect(wrapper.vm.successMessage).toBeNull();
    expect(wrapper.vm.error).toBe('Your password cannot be reset.');
  });

  it('check if the correct template is rendered', async () => {
    const wrapper: any = factory({ email: 'test@example.com' });

    await wrapper.vm.handleSubmit();
    expect(wrapper.find('.alert-danger').exists()).toBeTruthy();

    wrapper.find('#email').setValue('exotelis@mailbox.org');
    await wrapper.vm.handleSubmit();
    expect(wrapper.find('.alert-danger').exists()).toBeFalsy();
  });
});
