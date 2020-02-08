import Vue from 'vue';
import VueRouter from 'vue-router';
import routes from '@/router/routes';
import { i18n } from '@/i18n';

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
});

router.beforeEach((to, from, next) => {
  // Check if the user is authenticated
  if (to.meta.requiresAuth) {
    next('/login'); // Todo implement login func
  } else {
    // Set page title on route change
    document.title = to.meta.title ? i18n.t(to.meta.title) + ' | ' +
      process.env.VUE_APP_TITLE : process.env.VUE_APP_TITLE;
    next();
  }
});

export default router;
