import { config, mount } from '@vue/test-utils';
import NavigationItem from '../NavigationItem.vue';
import * as helpers from '@/helpers';
import { NavigationType } from '@/enum/NavigationType';

config.mocks!.$t = (key: any) => key;

const factory = (props = {}) => {
  return mount(NavigationItem, {
    name: 'navigation-item',
    propsData: {
      ...props
    },
    stubs: ['router-link']
  });
};

describe('NavigationItem.vue', () => {
  it('is Vue instance', () => {
    const component: any = factory({ config: { path: '/', type: NavigationType.Path } });
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the navigation generator', () => {
    const component: any = factory({ config: { path: '/', type: NavigationType.Path } });

    expect(component.props().portal).toBeNull();
    expect(component.props().showSubmenuClose).toBeFalsy();
    expect(component.props().showSubmenuTitle).toBeFalsy();
    expect(component.props().submenusRight).toBeFalsy();
  });

  it('create a link with a title of the / route', () => {
    const component: any = factory({ config: { path: '/', type: NavigationType.Path } });
    expect(component.find('.nav-link').attributes().to).toBe('/');
    expect(component.find('.nav-link').text()).toBe('page.title.dashboard');
  });

  it('create a link but it is hidden because of insufficient permissions', () => {
    const spy = jest.spyOn(helpers, 'checkPermissions');
    spy.mockReturnValue(false);

    const component: any = factory({ config: { path: '/users', type: NavigationType.Path } });
    expect(component.vm.config.type).toBe(NavigationType.Hidden);

    spy.mockRestore();
  });

  it('create a title that is not an anchor', () => {
    const component: any = factory({
      config: {
        title: 'page.title.dashboard',
        type: NavigationType.Title,
        children: [{ path: '/', type: NavigationType.Path }]
      }
    });
    expect(component.find('.nav-link').classes()).toContain('menu-subtitle');
    expect(component.find('.nav-link').text()).toBe('page.title.dashboard');
  });

  it('hide title element without children', () => {
    const component: any = factory({
      config: {
        title: 'page.title.dashboard',
        type: NavigationType.Title
      }
    });
    expect(component.vm.config.type).toBe(NavigationType.Hidden);
  });

  it('create a submenu with a title, a close button and some children', () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [
          { title: 'general.permissions', type: NavigationType.Title },
          { title: 'page.title.dashboard', path: '/', type: NavigationType.Path }
        ]
      },
      showSubmenuClose: true,
      showSubmenuTitle: true
    });

    expect(component.find('.nav-link').text()).toBe('general.system');
    expect(component.find('ul.submenu')).toBeDefined();
    expect(component.find('ul.submenu .submenu-title').text()).toBe('general.system');
    expect(component.find('ul.submenu .mdi-close').exists()).toBeTruthy();
    // Title and close button are children as well - so toBe 4 not just 2
    expect(component.findAll('ul.submenu > *').length).toBe(4);
  });

  it('create a submenu without a title, and a close button', () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [
          { title: 'general.permissions', type: NavigationType.Title },
          { title: 'page.title.dashboard', path: '/', type: NavigationType.Path }
        ]
      }
    });

    expect(component.find('ul.submenu .submenu-title').exists()).toBeFalsy();
    expect(component.find('ul.submenu .mdi-close').exists()).toBeFalsy();
    expect(component.findAll('ul.submenu > *').length).toBe(2);
  });

  it('right align submenu', () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [
          { title: 'general.permissions', type: NavigationType.Title },
          { title: 'page.title.dashboard', path: '/', type: NavigationType.Path }
        ]
      },
      submenusRight: true
    });

    expect(component.find('ul.submenu')).toBeDefined();
    expect(component.find('ul.submenu').classes()).toContain('right');
  });

  it('not right align submenu', () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [
          { title: 'general.permissions', type: NavigationType.Title },
          { title: 'page.title.dashboard', path: '/', type: NavigationType.Path }
        ]
      }
    });

    expect(component.find('ul.submenu')).toBeDefined();
    expect(component.find('ul.submenu').classes()).not.toContain('right');
  });

  it('hide submenu without children', () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler
      }
    });
    expect(component.vm.config.type).toBe(NavigationType.Hidden);
  });

  it('create each type and display an icon', () => {
    const path: any = factory({ config: { icon: 'test', path: '/', type: NavigationType.Path } });
    const title: any = factory({
      config: {
        icon: 'test',
        title: 'page.title.dashboard',
        type: NavigationType.Title,
        children: [{ path: '/', type: NavigationType.Path }]
      }
    });
    const submenu: any = factory({
      config: {
        icon: 'test',
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [{ path: '/', type: NavigationType.Path }]
      }
    });
    expect(path.find('.nav-link').classes()).toContain('test');
    expect(title.find('.nav-link').classes()).toContain('test');
    expect(submenu.find('.nav-link').classes()).toContain('test');
  });

  it('emit event \'close-all\' when clicking a router-link', async () => {
    const component: any = factory({ config: { path: '/', type: NavigationType.Path } });
    component.find('.nav-link').trigger('click');
    await component.vm.$nextTick();

    expect(component.emitted('close-all')).toBeTruthy();
  });

  it('emit event \'toggle-submenu\' when a submenu opens and closes', async () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [
          { title: 'general.permissions', type: NavigationType.Title },
          { title: 'page.title.dashboard', path: '/', type: NavigationType.Path }
        ]
      }
    });
    component.find('.nav-link').trigger('click');
    await component.vm.$nextTick();

    expect(component.emitted('toggle-submenu')).toBeTruthy();
    expect(component.vm.isSubmenuOpen).toBeTruthy();
  });

  it('emit event \'toggle-submenu\' when the close button is pressed', async () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [
          { title: 'general.permissions', type: NavigationType.Title },
          { title: 'page.title.dashboard', path: '/', type: NavigationType.Path }
        ]
      },
      showSubmenuClose: true
    });
    component.vm.isSubmenuOpen = true; // Mock open submenu
    component.find('.mdi-close').trigger('click');
    await component.vm.$nextTick();

    expect(component.emitted('toggle-submenu')).toBeTruthy();
    expect(component.vm.isSubmenuOpen).toBeFalsy();
  });

  it('emit no event when an element with type title is clicked', async () => {
    const component: any = factory({
      config: {
        title: 'general.permissions',
        type: NavigationType.Title,
        children: [{ path: '/', type: NavigationType.Path }]
      }
    });
    component.find('.nav-link').trigger('click');
    await component.vm.$nextTick();

    expect(component.emitted()).toEqual({});
  });

  it('add or remove class \'open\' when clicking on a submenuhandler or close button', async () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [{ path: '/', type: NavigationType.Path }]
      },
      showSubmenuClose: true
    });

    expect(component.find('ul.submenu').classes()).not.toContain('open');
    component.find('.nav-link').trigger('click');
    await component.vm.$nextTick();
    expect(component.find('ul.submenu').classes()).toContain('open');
    component.find('.nav-link').trigger('click');
    await component.vm.$nextTick();
    expect(component.find('ul.submenu').classes()).not.toContain('open');
    component.find('.nav-link').trigger('click');
    await component.vm.$nextTick();
    expect(component.find('ul.submenu').classes()).toContain('open');
    component.find('.mdi-close').trigger('click');
    await component.vm.$nextTick();
    expect(component.find('ul.submenu').classes()).not.toContain('open');
  });

  it('keep submenu visible, when not all children are hidden', async () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [
          { title: 'general.permissions', type: NavigationType.Title },
          {
            title: 'general.permissions',
            type: NavigationType.Title,
            children: [{ path: '/', type: NavigationType.Path }]
          }
        ]
      }
    });

    component.vm.onChildrenUpdate(component.vm.config.children);
    await component.vm.$nextTick();
    expect(component.vm.config.type).toBe(NavigationType.Submenuhandler);
  });

  it('hide submenu, when all children are hidden', async () => {
    const component: any = factory({
      config: {
        title: 'general.system',
        type: NavigationType.Submenuhandler,
        children: [
          { title: 'general.permissions', type: NavigationType.Title }
        ]
      }
    });

    component.vm.onChildrenUpdate(component.vm.config.children);
    await component.vm.$nextTick();
    expect(component.vm.config.type).toBe(NavigationType.Hidden);
  });
});
