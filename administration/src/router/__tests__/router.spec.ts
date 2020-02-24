import VueRouter from 'vue-router';
import router from '../router';

describe('router', () => {
  it('router is instance of VueRouter', () => {
    expect(router).toBeInstanceOf(VueRouter);
  });

  it('navigate to "/login" and change title', () => {
    router.push('/login');
    expect(router.currentRoute.path).toBe('/login');
    expect(document.title).toContain('Login');
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
});
