import { config, mount } from '@vue/test-utils';
import flushPromises from 'flush-promises';
import ConfirmEmailModal from '@/components/ui/ConfirmEmailModal.vue';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

const factory = (values = {}) => {
  return mount(ConfirmEmailModal, {
    mocks: {
      $route: {
        query: {
          ...values
        }
      },
      $router: {
        replace: (query: any) => { return query; }
      }
    },
    stubs: ['b-modal']
  });
};

describe('ConfirmEmailModal.vue', () => {
  it('is Vue instance', () => {
    const wrapper: any = factory();
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('check if error is false by default', () => {
    const wrapper: any = factory();
    expect(wrapper.vm.error).toBeFalsy();
  });

  it('check if message is null by default', () => {
    const wrapper: any = factory();
    expect(wrapper.vm.message).toBeNull();
  });

  it('check if modal dialog is shown', async () => {
    const wrapper: any = factory({ 'email-token': 'notavalidtoken' });
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'show');

    await flushPromises();
    expect(spy).toHaveBeenCalledWith('confirm-email-modal');

    spy.mockRestore();
  });

  it('check if error is faöse and message not null when valid token was submitted', async () => {
    const wrapper: any = factory({ 'email-token': 'ace97ba108eb058d18384e70cba8f13a91332165ea9b271b4f2d0003ae0f0337' });

    await flushPromises();
    expect(wrapper.vm.error).toBeFalsy();
    expect(wrapper.vm.message).not.toBeNull();
  });

  it('check if error is true and message not null when invalid token was submitted', async () => {
    const wrapper: any = factory({ 'email-token': 'notavalidtoken' });

    await flushPromises();
    expect(wrapper.vm.error).toBeTruthy();
    expect(wrapper.vm.message).not.toBeNull();
  });

  it('check if modal dialog was closed', () => {
    const wrapper: any = factory();
    const spy = jest.spyOn(wrapper.vm.$bvModal, 'hide');

    wrapper.vm.closeModal();
    expect(spy).toHaveBeenCalledWith('confirm-email-modal');

    spy.mockRestore();
  });

  it('check if close button triggers the closeModal method', () => {
    const wrapper: any = factory({ 'email-token': 'notavalidtoken' });
    const spy = jest.spyOn(wrapper.vm, 'closeModal');

    wrapper.find('#close-confirm-email-modal').trigger('click');
    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('check if §router.replace have been called.', () => {
    const wrapper: any = factory({ 'email-token': 'notavalidtoken' });
    const spy = jest.spyOn(wrapper.vm.$router, 'replace');

    wrapper.vm.hideModal();
    expect(spy).toHaveBeenCalledWith({ 'query': {} });

    spy.mockRestore();
  });
});
