import { mount, config } from '@vue/test-utils';
import LoginPage from '../LoginPage.vue';
import flushPromises from 'flush-promises';

jest.mock('@/services/AuthService');

config.mocks!.$t = (key: any) => key;

describe('LoginPage.vue', () => {
  let component: any;

  beforeEach(() => {
    component = mount(LoginPage, {
      mocks: {
        $router: {
          push: (route: any) => { component.vm.$emit('changeRoute'); return Promise.resolve(route); }
        }
      },
      stubs: ['router-link']
    });
  });

  it('is Vue instance', () => {
    expect(component.isVueInstance()).toBeTruthy();
  });

  it('check if submit button is disabled when username or password is empty', () => {
    component.find('#username').setValue('a'.repeat(7));
    expect(component.find('button').attributes().disabled).toBe('disabled');
    component.find('#username').setValue(' '.repeat(7));
    component.find('#password').setValue('b'.repeat(7));
    expect(component.find('button').attributes().disabled).toBe('disabled');
  });

  it('show error message, when an input field is empty', async () => {
    component.find('#password').setValue('b'.repeat(7));
    component.find('#password').setValue('');

    // 1.2 second timeout to wait for the validation
    await new Promise(resolve => setTimeout(resolve, 1200));

    expect(component.find('.invalid-feedback').text()).toBe('error.form.required.text');
  });

  it('check if the correct redirect is called', async () => {
    const spy = jest.spyOn(component.vm.$router, 'push');
    component.vm.form = {
      username: 'admin',
      password: 'admin'
    };

    component.vm.login();
    await component.vm.$nextTick();
    expect(spy).toHaveBeenCalledWith('/password-change');

    component.vm.login();
    await component.vm.$nextTick();
    expect(spy).toHaveBeenCalledWith('/');
  });

  it('sign the user in', async () => {
    component.find('#username').setValue('admin');
    component.find('#password').setValue('admin');
    component.find('form').trigger('submit');

    await component.vm.$nextTick();
    expect(component.vm.loading).toBeTruthy();

    await flushPromises();
    await component.vm.$nextTick();
    expect(component.emitted().changeRoute).toBeTruthy();
    expect(component.vm.loading).toBeFalsy();
  });

  it('fail to sign in', async () => {
    component.find('#username').setValue('admin');
    component.find('#password').setValue('wrongpassword');
    component.find('form').trigger('submit');

    await component.vm.$nextTick();

    await flushPromises();
    expect(component.vm.error).toBe('Something went wrong.');
  });
});
