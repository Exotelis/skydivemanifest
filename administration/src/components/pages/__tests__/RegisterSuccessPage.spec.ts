import { config, shallowMount } from '@vue/test-utils';
import RegisterSuccessPage from '../RegisterSuccessPage.vue';

config.mocks!.$t = (key: any) => key;

describe('RegisterSuccessPage.vue', () => {
  let component: any;

  beforeEach(() => {
    localStorage.clear();
    localStorage.setItem('tmpEmail', 'test@example.com');
    component = shallowMount(RegisterSuccessPage, {
      stubs: ['router-link']
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check if email is set correctly', () => {
    expect(component.vm.email).toBe('test@example.com');
  });

  it('check if email is unknown', () => {
    localStorage.removeItem('tmpEmail');
    const wrapper: any = shallowMount(RegisterSuccessPage, {
      mocks: {
        $router: {
          push: () => { component.vm.$emit('changeRoute'); return Promise.resolve(); }
        }
      },
      stubs: ['router-link']
    });
    expect(component.emitted().changeRoute).toBeTruthy();
  });

  it('check if key tmpEmail has been removed from localStorage', () => {
    expect(localStorage.getItem('tmpEmail')).toBe(null);
  });
});
