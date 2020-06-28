import { shallowMount } from '@vue/test-utils';
import NavigationGenerator from '../NavigationGenerator.vue';
import { NavigationType } from '@/enum/NavigationType';

describe('NavigationGenerator.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(NavigationGenerator, {
      propsData: {
        config: [
          { path: '/', type: NavigationType.Path },
          { title: 'general.system',
            type: NavigationType.Submenuhandler,
            children: [
              { title: 'general.permissions', type: NavigationType.Title },
              { path: '/users', type: NavigationType.Path }
            ]
          }
        ]
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('close all open submenus by settings the isSubmenuOpen prop of its children to false', () => {
    component.setData({ navigationItems: [{ $data: { isSubmenuOpen: true } }, { $data: { isSubmenuOpen: true } }] });
    const firstChild = component.vm.navigationItems[0];

    expect(component.vm.navigationItems.length).toBe(2);
    expect(firstChild.$data.isSubmenuOpen).toBeTruthy();
    component.vm.closeAll();
    expect(component.vm.navigationItems.length).toBe(0);
    expect(firstChild.$data.isSubmenuOpen).toBeFalsy();
  });

  it('open a submenu, when just one submenus can be opened', () => {
    component.setData({ navigationItems: [{ name: 'child1', $data: { isSubmenuOpen: true } }] });
    component.setProps({ onlyOneSubmenu: true });

    expect(component.vm.navigationItems.length).toBe(1);
    component.vm.toggleSubmenu({ name: 'newChild', $data: { isSubmenuOpen: true } } as any);
    expect(component.vm.navigationItems.length).toBe(1);
    expect(component.vm.navigationItems[0].name).toBe('newChild');
  });

  it('open a submenu, when more than one submenus can be opened', () => {
    component.setData({ navigationItems: [{ $data: { isSubmenuOpen: true } }, { $data: { isSubmenuOpen: true } }] });
    component.setProps({ onlyOneSubmenu: false });

    expect(component.vm.navigationItems.length).toBe(2);
    component.vm.toggleSubmenu({ $data: { isSubmenuOpen: true } } as any);
    expect(component.vm.navigationItems.length).toBe(3);
  });

  it('close a submenu', () => {
    component.setData({ navigationItems: [
      { name: 'child1', $data: { isSubmenuOpen: true } },
      { name: 'child2', $data: { isSubmenuOpen: true } },
      { name: 'child3', $data: { isSubmenuOpen: true } }
    ] });
    const secondChild = component.vm.navigationItems[1];
    secondChild.$data.isSubmenuOpen = false;

    component.vm.toggleSubmenu(secondChild);
    expect(component.vm.navigationItems.length).toBe(2);
    expect(component.vm.navigationItems[1].name).toBe('child3');
  });
});
