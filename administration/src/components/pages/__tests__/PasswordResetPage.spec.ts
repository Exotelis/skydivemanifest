import { mount, config } from '@vue/test-utils';
import PasswordResetPage from '../PasswordResetPage.vue';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

const factory = (values = {}) => {
  return mount(PasswordResetPage, {
    mocks: {
      $route: {
        query: { ...values }
      }
    },
    stubs: ['router-link']
  });
};

describe('PasswordResetPage.vue', () => {
  it('is Vue instance', () => {
    const wrapper: any = factory();
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check if email is predefined when the email url parameter is set', async () => {
    const wrapper: any = factory({ email: 'exotelis@mailbox.org' });
    expect(wrapper.vm.form.email).toBe('exotelis@mailbox.org');
    expect(wrapper.vm.emailPreFilled).toBeTruthy();
    await wrapper.vm.$nextTick();
    expect(wrapper.find('#email').attributes().readonly).toBeTruthy();
  });

  it('check if email is not predefined when the email url parameter is not set', async () => {
    const wrapper: any = factory();
    expect(wrapper.vm.form.email).toBe('');
    expect(wrapper.vm.emailPreFilled).toBeFalsy();
    await wrapper.vm.$nextTick();
    expect(wrapper.find('#email').attributes().readonly).toBeUndefined();
  });

  it('check if security token is predefined when the security token parameter is set', async () => {
    const wrapper: any = factory({ token: '6313a9097fb71922511d9cae56604912f02a71a7ec7e4eb330353ea72eca528a' });
    expect(wrapper.vm.form.token).toBe('6313a9097fb71922511d9cae56604912f02a71a7ec7e4eb330353ea72eca528a');
    expect(wrapper.vm.tokenPreFilled).toBeTruthy();
    await wrapper.vm.$nextTick();
    expect(wrapper.find('#token').attributes().readonly).toBeTruthy();
  });

  it('check if security token is not predefined when the security token parameter is not set', async () => {
    const wrapper: any = factory();
    expect(wrapper.vm.form.token).toBe('');
    expect(wrapper.vm.tokenPreFilled).toBeFalsy();
    await wrapper.vm.$nextTick();
    expect(wrapper.find('#token').attributes().readonly).toBeUndefined();
  });

  it('check if submit button is disabled when a required field is empty', () => {
    const wrapper: any = factory();
    expect(wrapper.find('button').attributes().disabled).toBe('disabled');
    wrapper.find('#email').setValue('exotelis@mailbox.org');
    wrapper.find('#password').setValue('secret');
    wrapper.find('#password_confirmation').setValue('secret');
    expect(wrapper.find('button').attributes().disabled).toBe('disabled');
    wrapper.find('#token').setValue('6313a9097fb71922511d9cae56604912f02a71a7ec7e4eb330353ea72eca528a');
    expect(wrapper.find('button').attributes().disabled).toBeUndefined();
  });

  it('check if method handleSubmit gets called', async () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm, 'handleSubmit');

    wrapper.find('form').trigger('submit');
    await wrapper.vm.$nextTick();
    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('check if submit was stopped because of validation errors', async () => {
    const wrapper: any = factory();
    wrapper.vm.errors = { email: 'Something went wrong.' };

    await wrapper.vm.handleSubmit();
    expect(wrapper.vm.hasValidationError()).toBeTruthy();
    expect(wrapper.vm.error).toBeNull();
  });

  it('check if password reset was successful', async () => {
    const wrapper: any = factory();

    wrapper.find('#email').setValue('exotelis@mailbox.org');
    wrapper.find('#password').setValue('secret');
    wrapper.find('#password_confirmation').setValue('secret');
    wrapper.find('#token').setValue('6313a9097fb71922511d9cae56604912f02a71a7ec7e4eb330353ea72eca528a');

    await wrapper.vm.handleSubmit();
    expect(wrapper.vm.error).toBeNull();
    expect(wrapper.vm.successMessage).toBe('Your password has been reset!');
  });

  it('check if password forgot failed', async () => {
    const wrapper: any = factory();

    wrapper.find('#email').setValue('test@example.com');
    wrapper.find('#password').setValue('secret');
    wrapper.find('#password_confirmation').setValue('secret');
    wrapper.find('#token').setValue('6313a9097fb71922511d9cae56604912f02a71a7ec7e4eb330353ea72eca528a');

    await wrapper.vm.handleSubmit();
    expect(wrapper.vm.successMessage).toBeNull();
    expect(wrapper.vm.error).toBe('The password reset token is invalid or expired.');
  });

  it('check if the correct template is rendered', async () => {
    const wrapper: any = factory();

    wrapper.find('#email').setValue('test@example.com');
    wrapper.find('#password').setValue('secret');
    wrapper.find('#password_confirmation').setValue('secret');
    wrapper.find('#token').setValue('6313a9097fb71922511d9cae56604912f02a71a7ec7e4eb330353ea72eca528a');

    await wrapper.vm.handleSubmit();
    expect(wrapper.find('.alert-danger').exists()).toBeTruthy();

    wrapper.find('#email').setValue('exotelis@mailbox.org');
    await wrapper.vm.handleSubmit();
    expect(wrapper.find('.alert-danger').exists()).toBeFalsy();
  });
});
