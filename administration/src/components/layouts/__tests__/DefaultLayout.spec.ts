import { config, shallowMount } from '@vue/test-utils';
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
          },
          params: {}
        }
      },
      stubs: ['router-view', 'portal-target']
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('close submenus of #main-navigation when clicking on the content container', async () => {
    component.vm.$refs.mainNavigation.closeAll = jest.fn();
    component.find('#default > #content').trigger('click');
    expect(component.vm.$refs.mainNavigation.closeAll).toHaveBeenCalled();
  });
});
