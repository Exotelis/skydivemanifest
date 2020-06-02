import Vue from 'vue';
import VueRouter from 'vue-router';
import { i18n } from '@/i18n';
import AuthService from '@/services/AuthService';
import routes from '@/router/routes';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  let auth: boolean = AuthService.checkAuth();

  if (to.path === '/login' && auth) {
    // If signed in - go to dashboard
    next('/');
  } else if (to.path !== '/login' && to.meta.requiresAuth && !auth) {
    // If not signed in and on restricted page - go to login
    next('login');
  } else {
    // Update title on route change
    const title = process.env.VUE_APP_TITLE || 'Skydivemanifest Administration';
    document.title = to.meta.title ? i18n.t(to.meta.title) + ' | ' + title : title;
    next();
  }
});

export default router;
