import VueRouter from 'vue-router';
import router from '../router';
import AuthService from '@/services/AuthService';

describe('router', () => {
  it('router is instance of VueRouter', () => {
    expect(router).toBeInstanceOf(VueRouter);
  });

  it('navigate to "/login" and change title', () => {
    router.push('/login');
    expect(router.currentRoute.path).toBe('/login');
    expect(document.title).toContain('Login');
  });

  it('navigate to "/" and change title', () => {
    const spy = jest.spyOn(AuthService, 'checkAuth').mockImplementation(() => true);

    router.push('/').catch(error => error);
    router.onReady(() => {
      expect(router.currentRoute.path).toBe('/');
      expect(document.title).toContain('Dashboard');
    });

    spy.mockRestore();
  });

  it('navigate to "/settings" but redirect to "/login"', () => {
    // Error must be caught, known issue => https://github.com/vuejs/vue-router/issues/2881
    router.push('/settings').catch(error => error);
    expect(router.currentRoute.path).toBe('/login');
    expect(document.title).toContain('Login');
  });

  it('navigate to page without a dynamic title', () => {
    router.addRoutes([{
      path: '/notitle',
      name: 'notitle'
    }]);
    router.push('/notitle');
    expect(router.currentRoute.path).toBe('/notitle');
    expect(document.title).toBe(process.env.VUE_APP_TITLE);
  });

  it('navigate to password-change', () => {
    const spyAuth = jest.spyOn(AuthService, 'checkAuth').mockImplementation(() => true);
    const spyPassword = jest.spyOn(AuthService, 'passwordChangeRequired').mockImplementation(() => true);
    router.push('/').catch(error => error);
    router.onReady(() => {
      expect(router.currentRoute.path).toBe('/password-change');
      expect(document.title).toContain('Change password');
    });

    spyAuth.mockRestore();
    spyPassword.mockRestore();
  });
});
