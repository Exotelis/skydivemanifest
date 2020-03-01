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

  it('close submenus of #main-navigation when clicking on the content container', async () => {
    component.vm.$refs.mainNavigation.closeAll = jest.fn();
    component.find('#default > div.content').trigger('click');
  });
});
