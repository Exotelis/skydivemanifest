import { mount, config } from '@vue/test-utils';
import { defaultLanguage } from '@/i18n';
import { Gender } from '@/enum/Gender';
import RegisterPage from '../RegisterPage.vue';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

describe('RegisterPage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = mount(RegisterPage, {
      mocks: {
        $router: {
          push: () => { component.vm.$emit('changeRoute'); return Promise.resolve(); }
        }
      },
      stubs: ['router-link']
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check if locale is set correctly', () => {
    expect(component.vm.form.locale).toBe(defaultLanguage);
  });

  it('check if timezone is 200', () => {
    // Mock timezone
    const resolvedOptions = Intl.DateTimeFormat.prototype.resolvedOptions;
    Intl.DateTimeFormat.prototype.resolvedOptions = jest.fn(() => {
      return {
        timeZone: undefined
      };
    });

    // Mock timezoneOffset - simulate CEST
    const getTimezoneOffset = Date.prototype.getTimezoneOffset;
    Date.prototype.getTimezoneOffset = jest.fn(() => -200); // eslint-disable-line no-extend-native

    const wrapper: any = mount(RegisterPage, { stubs: ['router-link'] });
    expect(wrapper.vm.form.timezone).toBe('200');

    // Restore defaults
    Intl.DateTimeFormat.prototype.resolvedOptions = resolvedOptions;
    Date.prototype.getTimezoneOffset = getTimezoneOffset; // eslint-disable-line no-extend-native
  });

  it('check if timezone is Europe/Berlin', () => {
    Intl.DateTimeFormat().resolvedOptions().timeZone = 'Europe/Berlin';
    const wrapper: any = mount(RegisterPage, { stubs: ['router-link'] });
    expect(wrapper.vm.form.timezone).toBe('Europe/Berlin');
  });

  it('check if submit button is disabled when a required field is empty', () => {
    expect(component.find('button').attributes().disabled).toBe('disabled');
    component.find('#dob').setValue('1970-01-01');
    component.find('#email').setValue('test@example.com');
    component.find('#firstname').setValue('John');
    component.find('#lastname').setValue('Doe');
    component.find('#password').setValue('secret');
    component.find('#password_confirmation').setValue(' ');
    expect(component.find('button').attributes().disabled).toBe('disabled');
    component.find('#password_confirmation').setValue('secret');
    expect(component.find('button').attributes().disabled).toBeUndefined();
  });

  it('check if method handleSubmit gets called', async () => {
    const spy = jest.spyOn(component.vm, 'handleSubmit');
    component.find('#dob').setValue('1970-01-01');
    component.find('#email').setValue('test@example.com');
    component.find('#firstname').setValue('John');
    component.find('#lastname').setValue('Doe');
    component.find('#password').setValue('secret');
    component.find('#password_confirmation').setValue('secret');

    component.find('form').trigger('submit');
    await component.vm.$nextTick();
    expect(spy).toHaveBeenCalled();

    spy.mockRestore();
  });

  it('check if submit was stopped because of validation errors', async () => {
    component.vm.errors = { email: 'Something went wrong.' };

    await component.vm.handleSubmit();
    expect(component.vm.hasValidationError()).toBeTruthy();
    expect(component.emitted().changeRoute).toBeFalsy();
    expect(component.vm.error).toBeNull();
  });

  it('check if registration was successful', async () => {
    component.vm.form = {
      dob: '1970-01-01',
      email: 'exotelis@mailbox.org',
      firstname: 'John',
      gender: Gender.u,
      lastname: 'Doe',
      password: 'secret',
      password_confirmation: 'secret'
    };

    expect(component.find('h3').text()).toBe('general.signUp');
    await component.vm.handleSubmit();
    expect(component.vm.email).toBe('exotelis@mailbox.org');
    expect(component.vm.error).toBeNull();
    expect(component.vm.dirty).toBeFalsy();
    expect(component.find('h3').text()).toBe('page.register.successTitle');
  });

  it('check if registration failed', async () => {
    component.vm.form = {
      dob: '1970-01-01',
      email: 'test@example.com',
      firstname: 'John',
      gender: Gender.u,
      lastname: 'Doe',
      password: 'secret',
      password_confirmation: 'secret'
    };

    await component.vm.handleSubmit();
    expect(component.emitted().changeRoute).toBeFalsy();
    expect(component.vm.error).toBe('The registration failed.');
  });

  it('check if form is dirty after some input', async () => {
    expect(component.vm.dirty).toBeFalsy();
    component.find('#email').setValue('exotelis@example.com');
    expect(component.vm.dirty).toBeTruthy();
  });
});
