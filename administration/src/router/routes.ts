import LoginPage from '@/components/pages/LoginPage.vue';

export default [
  {
    path: '/',
    name: 'dashboard',
    component: () => import(/* webpackChunkName: "dashboard" */ '@/components/pages/DashboardPage.vue'),
    meta: {
      title: 'pages.title.dashboard',
      requiresAuth: true
    }
  },
  {
    path: '/settings',
    name: 'settings',
    component: () => import(/* webpackChunkName: "settings" */ '@/components/pages/SettingsPage.vue'),
    meta: {
      title: 'pages.title.settings',
      requiresAuth: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: LoginPage,
    meta: {
      title: 'pages.title.login',
      layout: 'Welcome',
      requiresAuth: false
    }
  },
  {
    path: '*',
    redirect: '/'
  }
];
