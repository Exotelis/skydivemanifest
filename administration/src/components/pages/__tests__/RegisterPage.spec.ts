import { mount, config } from '@vue/test-utils';
import RegisterPage from '../RegisterPage.vue';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

describe('RegisterPage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = mount(RegisterPage);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });
});
