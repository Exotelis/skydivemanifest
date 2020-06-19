import LoginPage from '@/components/pages/LoginPage.vue';

const routes = [
  {
    path: '*',
    redirect: '/'
  },
  {
    path: '/',
    name: 'dashboard',
    component: () => import(/* webpackChunkName: "dashboard" */ '@/components/pages/DashboardPage.vue'),
    meta: {
      title: 'page.title.dashboard',
      requiresAuth: true
    }
  },
  {
    path: '/aircrafts',
    name: 'aircrafts',
    meta: {
      title: 'page.title.aircrafts',
      requiresAuth: true
    }
  },
  {
    path: '/bookings',
    name: 'bookings',
    meta: {
      title: 'page.title.bookings',
      requiresAuth: true
    }
  },
  {
    path: '/login',
    name: 'login',
    component: LoginPage,
    meta: {
      title: 'page.title.login',
      layout: 'Welcome',
      requiresAuth: false
    }
  },
  {
    path: '/manifest',
    name: 'manifest',
    meta: {
      title: 'page.title.manifest',
      requiresAuth: true
    }
  },
  {
    path: '/password-change',
    name: 'password-change',
    component: () => import(/* webpackChunkName: "passwordChange" */ '@/components/pages/PasswordChangePage.vue'),
    meta: {
      title: 'page.title.passwordChange',
      layout: 'Welcome',
      requiresAuth: true
    }
  },
  {
    path: '/payments',
    name: 'payments',
    meta: {
      title: 'page.title.payments',
      requiresAuth: true
    }
  },
  {
    path: '/register',
    name: 'register',
    component: () => import(/* webpackChunkName: "register" */ '@/components/pages/RegisterPage.vue'),
    meta: {
      title: 'page.title.register',
      layout: 'Welcome',
      requiresAuth: false
    }
  },
  {
    path: '/register-success',
    name: 'register-success',
    component: () => import(/* webpackChunkName: "registerSuccess" */ '@/components/pages/RegisterSuccessPage.vue'),
    meta: {
      title: 'page.title.registerSuccess',
      layout: 'Welcome',
      requiresAuth: false
    }
  },
  {
    path: '/settings',
    name: 'settings',
    component: () => import(/* webpackChunkName: "settings" */ '@/components/pages/SettingsPage.vue'),
    meta: {
      title: 'page.title.settings',
      requiresAuth: true
    }
  },
  {
    path: '/skydiver',
    name: 'skydiver',
    meta: {
      title: 'page.title.skydiver',
      requiresAuth: true
    }
  },
  {
    path: '/staff',
    name: 'staff',
    meta: {
      title: 'page.title.staff',
      requiresAuth: true
    }
  },
  {
    path: '/system',
    name: 'System',
    meta: {
      title: 'page.title.system',
      requiresAuth: true
    }
  },
  {
    path: '/tickets',
    name: 'tickets',
    meta: {
      title: 'page.title.tickets',
      requiresAuth: true
    }
  },
  {
    path: '/users',
    name: 'users',
    meta: {
      title: 'page.title.users',
      requiresAuth: true
    }
  },
  {
    path: '/userroles',
    name: 'userroles',
    meta: {
      title: 'page.title.userroles',
      requiresAuth: true
    }
  }
];

function flattenRoutes (routes: Array<object>, parentPath: string = ''): Array<any> {
  let flatRoutes: Array<any> = [];
  for (let i in routes) {
    const route: any = routes[i];
    const path: string = parentPath !== '' && (route.path).charAt(0) !== '/' ? parentPath + '/' + route.path
      : parentPath + route.path;

    flatRoutes.push([ path, route ]);
    if (typeof route.children !== 'undefined' && route.children.length > 0) {
      flatRoutes = [ ...flatRoutes, ...flattenRoutes(route.children, path) ];
    }
  }

  return flatRoutes;
}

export default routes;
export const routesMap = new Map(flattenRoutes(routes));
