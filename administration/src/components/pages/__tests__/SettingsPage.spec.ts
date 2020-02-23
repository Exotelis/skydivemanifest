import { shallowMount, config } from '@vue/test-utils';
import SettingsPage from '../SettingsPage.vue';

config.mocks!.$t = (key: any) => key;

describe('SettingsPage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(SettingsPage);
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });
});
