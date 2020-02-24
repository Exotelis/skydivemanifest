import { shallowMount, config } from '@vue/test-utils';
import DashboardPage from '../DashboardPage.vue';

config.mocks!.$t = (key: any) => key;

describe('DashboardPage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(DashboardPage);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });
});
