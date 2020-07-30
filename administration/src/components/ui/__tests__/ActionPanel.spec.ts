import { config, shallowMount } from '@vue/test-utils';
import { Route } from 'vue-router';
import ActionPanel from '@/components/ui/ActionPanel.vue';

config.mocks!.$t = (key: any) => key;

describe('ActionPanel.vue', () => {
  let component: any;

  beforeEach(() => {
    component = shallowMount(ActionPanel, {
      mocks: {
        $router: {
          go: (steps: number) => steps,
          push: (route: Route) => route.path
        }
      }
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check default values of the action panel', () => {
    expect(component.props().backButton).toBeFalsy();
    expect(component.props().backPath).toBeNull();
    expect(component.props().container).toBeFalsy();
  });

  it('check if back button is displayed', () => {
    component.setProps({ backButton: true });
    expect(component.find('.btn.btn-link.mdi-arrow-left').text()).toBe('general.back');
  });

  it('check if backPath is set', () => {
    component.setProps({ backPath: '/dashboard' });
    expect(component.vm.backPath).toBe('/dashboard');
  });

  it('go 1 step back in the browser history', () => {
    const spy = jest.spyOn(component.vm.$router, 'go');
    component.setProps({ backButton: true });

    component.find('.btn.btn-link.mdi-arrow-left').trigger('click');
    expect(spy).toHaveBeenCalled();
    expect(spy).toHaveReturnedWith(-1);

    spy.mockRestore();
  });

  it('go to the given path', () => {
    const spy = jest.spyOn(component.vm.$router, 'push');
    component.setProps({ backButton: true, backPath: '/dashboard' });

    component.find('.btn.btn-link.mdi-arrow-left').trigger('click');
    expect(spy).toHaveBeenCalled();
    expect(spy).toHaveReturnedWith('/dashboard');

    spy.mockRestore();
  });

  it('check if container is set', () => {
    component.setProps({ backButton: true, container: true });

    expect(component.vm.container).toBeTruthy();
    expect(component.find('.container').exists()).toBeTruthy();
  });

  it('check if container is not set', () => {
    component.setProps({ backButton: true, container: false });

    expect(component.vm.container).toBeFalsy();
    expect(component.find('.container').exists()).toBeFalsy();
  });
});
