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
      title: 'pages.title.dashboard',
      requiresAuth: false
    }
  },
  {
    path: '/aircrafts',
    name: 'aircrafts',
    meta: {
      title: 'pages.title.aircrafts',
      requiresAuth: false
    }
  },
  {
    path: '/bookings',
    name: 'bookings',
    meta: {
      title: 'pages.title.bookings',
      requiresAuth: false
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
    path: '/manifest',
    name: 'manifest',
    meta: {
      title: 'pages.title.manifest',
      requiresAuth: false
    }
  },
  {
    path: '/payments',
    name: 'payments',
    meta: {
      title: 'pages.title.payments',
      requiresAuth: false
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
    path: '/skydiver',
    name: 'skydiver',
    meta: {
      title: 'pages.title.skydiver',
      requiresAuth: false
    }
  },
  {
    path: '/staff',
    name: 'staff',
    meta: {
      title: 'pages.title.staff',
      requiresAuth: false
    }
  },
  {
    path: '/system',
    name: 'System',
    meta: {
      title: 'pages.title.system',
      requiresAuth: false
    }
  },
  {
    path: '/tickets',
    name: 'tickets',
    meta: {
      title: 'pages.title.tickets',
      requiresAuth: false
    }
  },
  {
    path: '/users',
    name: 'users',
    meta: {
      title: 'pages.title.users',
      requiresAuth: false
    }
  },
  {
    path: '/userroles',
    name: 'userroles',
    meta: {
      title: 'pages.title.userroles',
      requiresAuth: false
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
