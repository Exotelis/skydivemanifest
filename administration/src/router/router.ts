import Vue from 'vue';
import VueRouter from 'vue-router';
import { ToastPlugin } from 'bootstrap-vue';
import { checkPermissions } from '@/helpers';
import { i18n } from '@/i18n';
import AuthService from '@/services/AuthService';
import routes from '@/router/routes';

Vue.use(ToastPlugin);
Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  linkExactActiveClass: 'active',
  routes
});

router.beforeEach((to, from, next) => {
  let auth: boolean = AuthService.checkAuth();

  if (AuthService.passwordChangeRequired() && to.path !== '/password-change') {
    // If user need to change the password
    next({ path: '/password-change', query: to.query });
  } else if (!to.meta.requiresAuth && auth) {
    // If signed in and on page that don't requires auth - go to dashboard
    next({ path: '/', query: to.query });
  } else if (to.path !== '/login' && to.meta.requiresAuth && !auth) {
    // If not signed in and on restricted page - go to login
    next({ path: 'login', query: to.query });
  } else if (auth && to.meta.permissions) {
    // Permission handling
    if (checkPermissions(to.meta.permissions)) {
      // Success - User has permissions
      updateTitle(to.meta.title);
      next();
    } else {
      if (from.name === null) {
        // If page refresh - go to dashboard
        next('/');
      } else {
        // When trying to visit page without permissions (Could happen when a link is not hidden)
        firePermissionsDeniedToast(to.meta.title);
        next(false);
      }
    }
  } else {
    // Update title on route change
    updateTitle(to.meta.title);
    next();
  }
});

function firePermissionsDeniedToast (title: string): void {
  if (process.env.NODE_ENV === 'test') {
    return;
  }

  title = i18n.t(title) as string;
  router.app.$bvToast.toast(i18n.t('general.noPermissionsText', { title: title }) as string, {
    title: i18n.t('general.noPermissionsTitle') as string,
    autoHideDelay: 5000,
    appendToast: false,
    variant: 'danger',
    solid: true
  });
}

function updateTitle (title: string): void {
  const titleSuffix = process.env.VUE_APP_TITLE || 'Skydivemanifest Administration';
  document.title = title ? i18n.t(title) + ' | ' + titleSuffix : titleSuffix;
}

export default router;
