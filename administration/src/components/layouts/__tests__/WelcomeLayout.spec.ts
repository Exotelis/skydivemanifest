import { config, shallowMount } from '@vue/test-utils';
import WelcomeLayout from '../WelcomeLayout.vue';

config.mocks!.$t = (key: any) => key;

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

  it('assign "' + new Date().getHours() + '" to data key "time"', () => {
    expect(component.vm.time).toBe(new Date().getHours());
  });

  it('assign "20" to data key "time"', () => {
    component.setData({ time: new Date('2020-01-26T20:00:00').getHours() });
    expect(component.vm.time).toBe(20);
  });

  it('return "page.login.title.morning"', () => {
    component.setData({ time: new Date('2020-01-26T05:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('page.login.title.morning');
  });

  it('return "page.login.title.noon"', () => {
    component.setData({ time: new Date('2020-01-26T12:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('page.login.title.noon');
  });

  it('return "page.login.title.afternoon"', () => {
    component.setData({ time: new Date('2020-01-26T15:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('page.login.title.afternoon');
  });

  it('return "page.login.title.evening"', () => {
    component.setData({ time: new Date('2020-01-26T18:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('page.login.title.evening');
  });

  it('return "page.login.title.other"', () => {
    component.setData({ time: new Date('2020-01-26T00:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('page.login.title.other');
  });
});
