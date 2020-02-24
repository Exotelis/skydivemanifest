import { shallowMount } from '@vue/test-utils';
import App from '@/App.vue';

const factory = (values = {}) => {
  return shallowMount(App, {
    mocks: {
      $route: {
        meta: {
          ...values
        }
      }
    },
    stubs: ['router-link', 'router-view']
  });
};

describe('App.vue', () => {
  it('is Vue instance', () => {
    const wrapper: any = factory();
    expect(wrapper.isVueInstance()).toBeTruthy();
  });

  it('return the default layout "DefaultLayout"', () => {
    const wrapper: any = factory();
    expect(wrapper.vm.getLayout).toBe('DefaultLayout');
  });

  it('return "WelcomeLayout"', () => {
    const wrapper: any = factory({ layout: 'Welcome' });
    expect(wrapper.vm.getLayout).toBe('WelcomeLayout');
  });

  it('check if the default layout gets rendered correctly', () => {
    const wrapper: any = factory();
    expect(wrapper.find('defaultlayout-stub').exists()).toBeTruthy();
  });

  it('check if the welcome layout gets rendered correctly', () => {
    const wrapper: any = factory({ layout: 'Welcome' });
    expect(wrapper.find('welcomelayout-stub').exists()).toBeTruthy();
  });
});
