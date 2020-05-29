<template>
  <div id="default">
    <aside>
      <div class="wrapper">
        <logo-license-status></logo-license-status>
        <nav id="main-navigation">
          <navigation-generator class="flex-column" ref="mainNavigation" :config="mainNavigationConfig">
          </navigation-generator>
        </nav>
      </div>
    </aside>

    <div class="content" @click="closeMainnavigationSubmenus">
      <header class="d-flex align-items-center">
        <div class="container-fluid">
          <div class="row">
            <div class="col d-flex align-items-center">
              <h1 class="h2 m-0 font-weight-normal">{{ $t( $route.meta.title ) }}</h1>
            </div>
            <div class="col-md-auto d-none d-md-block">
              <language-selector></language-selector>
            </div>
          </div>
        </div>
      </header>
      <main>
        <div class="container-fluid">
          <div class="row">
            <div class="col">
              <slot><router-view></router-view></slot>
            </div>
          </div>
        </div>
      </main>
      <footer>
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
import { NavigationGeneratorInterface } from '@/components/navigation/NavigationGeneratorInterface';
import { NavigationModel } from '@/components/navigation/NavigationModel';
import { NavigationType } from '@/components/navigation/NavigationType';
import LanguageSelector from '@/components/ui/LanguageSelector.vue';
import LogoLicenseStatus from '@/components/ui/LogoLicenseStatus.vue';
import NavigationGenerator from '@/components/navigation/NavigationGenerator.vue';

@Component({
  components: {
    LanguageSelector,
    LogoLicenseStatus,
    NavigationGenerator
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
    { icon: 'mdi-cog',
      title: 'general.system',
      type: NavigationType.Submenuhandler,
      children: [
        { title: 'general.permissions', type: NavigationType.Title },
        { title: 'general.permissions', type: NavigationType.Submenuhandler },
        { path: '/userroles', type: NavigationType.Path }
      ]
    },
    { icon: 'mdi-calendar', path: '/bookings', type: NavigationType.Path },
    { icon: 'mdi-receipt', path: '/payments', type: NavigationType.Path },
    { icon: 'mdi-cog',
      title: 'general.system',
      type: NavigationType.Submenuhandler,
      children: [
        { title: 'general.permissions', type: NavigationType.Title },
        { path: '/users', type: NavigationType.Path },
        { path: '/userroles', type: NavigationType.Path },
        { title: 'general.othersettings', type: NavigationType.Title },
        { path: '/settings', type: NavigationType.Path }
      ]
    }
  ];

  closeMainnavigationSubmenus (): void {
    (this.$refs.mainNavigation as NavigationGeneratorInterface).closeAll();
  }
}
</script>
