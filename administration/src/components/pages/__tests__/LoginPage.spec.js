import { shallowMount, config } from '@vue/test-utils';
import LoginPage from '../LoginPage';

config.mocks.$t = key => key;

describe('LoginPage.vue', () => {
  let component;

  beforeEach(() => {
    component = shallowMount(LoginPage);
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

  it('return "login.title.morning"', () => {
    component.setData({ time: new Date('2020-01-26T05:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('login.title.morning');
  });

  it('return "login.title.noon"', () => {
    component.setData({ time: new Date('2020-01-26T12:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('login.title.noon');
  });

  it('return "login.title.afternoon"', () => {
    component.setData({ time: new Date('2020-01-26T15:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('login.title.afternoon');
  });

  it('return "login.title.evening"', () => {
    component.setData({ time: new Date('2020-01-26T18:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('login.title.evening');
  });

  it('return "login.title.other"', () => {
    component.setData({ time: new Date('2020-01-26T00:00:00').getHours() });
    expect(component.vm.getTimeBasedTitle).toBe('login.title.other');
  });
});
