<template>
  <div id="default" class="d-flex">
    <portal-target class="main-navigation-submenu" name="main-navigation" multiple></portal-target>
    <aside>
      <logo-license-status></logo-license-status>
      <nav id="main-navigation">
        <navigation-generator class="flex-column"
                              only-one-submenu
                              ref="mainNavigation"
                              show-submenu-close
                              show-submenu-title
                              :config="mainNavigationConfig"
                              :portal="'main-navigation'">
        </navigation-generator>
      </nav>
    </aside>

    <div id="content" class="flex-grow-1" @click="closeMainnavigationSubmenus">
      <header class="align-items-center d-flex">
        <div class="container-fluid">
          <div class="row">
            <div class="col d-flex align-items-center">
              <h1 class="h2 m-0 font-weight-normal">
                {{ $t( $route.meta.title ) }}
                <span v-if="$route.params.id">#{{ $route.params.id }}</span>
              </h1>
            </div>
            <user-menu></user-menu>
          </div>
        </div>
      </header>
      <main>
        <div class="container-fluid">
          <div class="row">
            <div class="col my-3">
              <slot><router-view></router-view></slot>
            </div>
          </div>
        </div>
      </main>
      <footer class="border-top py-4">
        <div class="container-fluid">
          <div class="row">
            <div class="col">
              <i18n path="general.copyright">
                <template v-slot:year>{{ $d(new Date(), 'year') }}</template>
                <template v-slot:author><strong>{{ $t('app.title') }}</strong></template>
              </i18n>
            </div>
            <div class="col-lg-auto mt-2 mt-lg-0">
              <a href="https://github.com/exotelis/skydivemanifest/issues" target="_blank">
                {{ $t('general.reportBug') }}
              </a>
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { NavigationGeneratorInterface } from '@/interfaces/NavigationGeneratorInterface';
import { NavigationModel } from '@/models/NavigationModel';
import { NavigationType } from '@/enum/NavigationType';
import LogoLicenseStatus from '@/components/ui/LogoLicenseStatus.vue';
import NavigationGenerator from '@/components/navigation/NavigationGenerator.vue';
import UserMenu from '@/components/ui/UserMenu.vue';

@Component({
  components: {
    LogoLicenseStatus,
    NavigationGenerator,
    UserMenu
  }
})
export default class DefaultLayout extends Vue {
  mainNavigationConfig: Array<NavigationModel> = [
    { icon: 'mdi-speedometer', path: '/', type: NavigationType.Path },
    { icon: 'mdi-airplane', path: '/aircrafts', type: NavigationType.Path },
    { icon: 'mdi-account-group', path: '/staff', type: NavigationType.Path },
    { icon: 'mdi-parachute', path: '/skydiver', type: NavigationType.Path },
    { icon: 'mdi-ticket', path: '/tickets', type: NavigationType.Path },
    { icon: 'mdi-monitor-dashboard', path: '/manifest', type: NavigationType.Path },
    { icon: 'mdi-calendar', path: '/bookings', type: NavigationType.Path },
    { icon: 'mdi-receipt', path: '/payments', type: NavigationType.Path },
    {
      icon: 'mdi-cogs',
      title: 'general.system',
      type: NavigationType.Submenuhandler,
      children: [
        {
          title: 'general.permissions',
          type: NavigationType.Title,
          children: [
            { path: '/users', type: NavigationType.Path },
            { path: '/users/trashed', type: NavigationType.Path },
            { path: '/user-roles', type: NavigationType.Path }
          ]
        },
        {
          title: 'general.othersettings',
          type: NavigationType.Title,
          children: [
            { path: '/settings', type: NavigationType.Path }
          ]
        }
      ]
    }
  ];

  closeMainnavigationSubmenus (): void {
    (this.$refs.mainNavigation as NavigationGeneratorInterface).closeAll();
  }
}
</script>
