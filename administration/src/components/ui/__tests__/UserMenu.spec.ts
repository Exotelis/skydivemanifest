import { config, shallowMount } from '@vue/test-utils';
import UserMenu from '@/components/ui/UserMenu.vue';

config.mocks!.$t = (key: any) => key;

describe('UserMenu.vue', () => {
  let component: any;

  beforeAll(() => {
    document.cookie = 'XSRF-TOKEN=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyIjp7ImVtYWlsIjoiZXhvdGVsaXNAbWFpbGJveC5vcmciLCJmaXJzdG5hbWUiOiJKb2huIn19';
  });

  afterAll(() => {
    document.cookie = 'XSRF-TOKEN= ; expires = Thu, 01 Jan 1970 00:00:00 GMT';
  });

  beforeEach(() => {
    component = shallowMount(UserMenu);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('set the user', () => {
    expect(component.vm.user).toStrictEqual({
      email: 'exotelis@mailbox.org',
      firstname: 'John'
    });
  });
});
