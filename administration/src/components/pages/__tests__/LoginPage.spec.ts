import { mount, config } from '@vue/test-utils';
import LoginPage from '../LoginPage.vue';

config.mocks!.$t = (key: any) => key;

describe('LoginPage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = mount(LoginPage);
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

  it('check if submit button is disabled when username or password is empty', () => {
    component.setData({ form: { username: ' '.repeat(7), password: ''.repeat(7) } });
    expect(component.find('button').attributes().disabled).toBe('disabled');
    component.find('#username').setValue('a'.repeat(7));
    expect(component.find('button').attributes().disabled).toBe('disabled');
    component.find('#username').setValue(' '.repeat(7));
    component.find('#password').setValue('b'.repeat(7));
    expect(component.find('button').attributes().disabled).toBe('disabled');
  });

  it('show error message, when an input field is empty', async () => {
    component.find('#password').setValue('b'.repeat(7));
    component.find('button').trigger('click');
    await component.vm.$nextTick();
    expect(component.find('.invalid-feedback').text()).toBe('error.form.required.text');
  });

  it('handle login routine when the form is submitted', async () => {
    component.find('form').trigger('submit');
    await component.vm.$nextTick();
    expect(component.vm.loading).toBeTruthy(); // TODO update when actual login route is implemented
  });
});
