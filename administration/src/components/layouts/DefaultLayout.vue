<template>
  <div id="default">
    <aside>
      <logo-license-status></logo-license-status>
      <main-navigation></main-navigation>
    </aside>

    <div class="content">
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

<script>
import LanguageSelector from '@/components/ui/LanguageSelector';
import LogoLicenseStatus from '@/components/ui/LogoLicenseStatus';
import MainNavigation from '@/components/ui/MainNavigation';

export default {
  name: 'DefaultLayout',
  components: {
    LanguageSelector,
    LogoLicenseStatus,
    MainNavigation
  }
};
</script>
