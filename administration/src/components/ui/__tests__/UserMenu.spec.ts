import { config, shallowMount } from '@vue/test-utils';
import UserMenu from '@/components/ui/UserMenu.vue';

config.mocks!.$t = (key: any) => key;

describe('UserMenu.vue', () => {
  let component: any;
  let user: any = {
    email: 'exotelis@mailbox.org',
    firstname: 'John'
  };

  beforeAll(() => {
    localStorage.setItem('user', JSON.stringify(user));
  });

  afterAll(() => {
    localStorage.removeItem('user');
  });

  beforeEach(() => {
    component = shallowMount(UserMenu);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('set the user', () => {
    expect(component.vm.user).toStrictEqual(user);
  });
});
