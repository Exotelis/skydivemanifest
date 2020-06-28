import VueRouter from 'vue-router';
import router from '../router';
import AuthService from '@/services/AuthService';

describe('router', () => {
  it('router is instance of VueRouter', () => {
    expect(router).toBeInstanceOf(VueRouter);
  });

  it('navigate to "/login" and change title', async () => {
    await router.push('/login');
    expect(router.currentRoute.path).toBe('/login');
    expect(document.title).toContain('Login');
  });

  it('navigate to "/" and change title', async () => {
    const spy = jest.spyOn(AuthService, 'checkAuth').mockImplementation(() => true);

    await router.push('/');
    expect(router.currentRoute.path).toBe('/');
    expect(document.title).toContain('Dashboard');

    spy.mockRestore();
  });

  it('navigate to "/settings" but redirect to "/login"', async () => {
    // Error must be caught, known issue => https://github.com/vuejs/vue-router/issues/2881
    await router.push('/settings').catch(error => error);
    expect(router.currentRoute.path).toBe('/login');
    expect(document.title).toContain('Login');
  });

  it('navigate to page without a dynamic title', async () => {
    router.addRoutes([{
      path: '/notitle',
      name: 'notitle'
    }]);
    await router.push('/notitle');
    expect(router.currentRoute.path).toBe('/notitle');
    expect(document.title).toBe(process.env.VUE_APP_TITLE);
  });

  it('navigate to password-change', async () => {
    const spyAuth = jest.spyOn(AuthService, 'checkAuth').mockImplementation(() => true);
    const spyPassword = jest.spyOn(AuthService, 'passwordChangeRequired').mockImplementation(() => true);

    await router.push('/').catch(error => error);
    expect(router.currentRoute.path).toBe('/password-change');
    expect(document.title).toContain('Change password');

    spyAuth.mockRestore();
    spyPassword.mockRestore();
  });

  it('redirect to "/notitle" when the user has sufficient permissions', async () => {
    const spy = jest.spyOn(AuthService, 'checkAuth').mockImplementation(() => true);
    document.cookie = 'XSRF-TOKEN=eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZXMiOlsicGVybWlzc2lvbnM6cmVhZCJdLCJ1c2VyIjp7fX0';
    router.addRoutes([{
      path: '/permissions',
      name: 'permissions',
      meta: { permissions: ['permissions:read'], requiresAuth: true }
    }]);

    await router.push('/permissions').catch(error => error);
    expect(router.currentRoute.path).toBe('/permissions');

    spy.mockRestore();
  });

  it('redirect to dashboard with insufficient permissions', async () => {
    const spyAuth = jest.spyOn(AuthService, 'checkAuth').mockImplementation(() => true);
    const spyPassword = jest.spyOn(AuthService, 'passwordChangeRequired').mockImplementation(() => false);

    router.addRoutes([{
      path: '/nopermissions',
      name: 'nopermissions',
      meta: { permissions: ['nopermissions:read'], requiresAuth: true }
    }]);

    await router.push('/').catch(error => error);
    await router.push('/nopermissions').catch(error => error);
    expect(router.currentRoute.path).toBe('/');
    expect(document.title).toContain('Dashboard');

    spyAuth.mockRestore();
    spyPassword.mockRestore();
  });
});
