import { shallowMount, config } from '@vue/test-utils';
import DefaultLayout from '../DefaultLayout.vue';

config.mocks!.$t = (key: any) => key;

describe('DefaultLayout.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(DefaultLayout, {
      mocks: {
        $route: {
          meta: {
            title: 'TestTitle'
          }
        }
      },
      stubs: ['router-view']
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });
});
