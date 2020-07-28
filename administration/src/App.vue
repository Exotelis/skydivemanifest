<template>
  <component :is="getLayout">
    <router-view></router-view>

    <!-- Modal components -->
    <confirm-email-modal></confirm-email-modal>
    <sign-in-modal></sign-in-modal>
  </component>
</template>

<script lang="ts">
import Vue from 'vue';
import PortalVue from 'portal-vue';
import { Component } from 'vue-property-decorator';

import Popper from 'popper.js';
// see: https://github.com/twbs/bootstrap/issues/23590
Popper.Defaults.modifiers!.computeStyle!.gpuAcceleration = false;

// Register Plugins
// TODO: Remove as soon as we moved to vue 3
Vue.use(PortalVue);

// Register async components globally
/* istanbul ignore next */
Vue.component('DefaultLayout', () => import('@/components/layouts/DefaultLayout.vue'));
/* istanbul ignore next */
Vue.component('WelcomeLayout', () => import('@/components/layouts/WelcomeLayout.vue'));
/* istanbul ignore next */
Vue.component('ConfirmEmailModal', () => import('@/components/ui/ConfirmEmailModal.vue'));
/* istanbul ignore next */
Vue.component('SignInModal', () => import('@/components/ui/SignInModal.vue'));

@Component
export default class App extends Vue {
  get getLayout (): string {
    return (this.$route.meta.layout || 'Default') + 'Layout';
  }
}
</script>

<style lang="scss">
@import './assets/scss/app.scss';
</style>
