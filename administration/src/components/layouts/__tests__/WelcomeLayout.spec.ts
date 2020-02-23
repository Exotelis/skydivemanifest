import { shallowMount } from '@vue/test-utils';
import WelcomeLayout from '../WelcomeLayout.vue';

describe('WelcomeLayout.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(WelcomeLayout, {
      stubs: ['router-view']
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });
});
