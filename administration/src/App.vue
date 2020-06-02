<template>
  <component :is="getLayout">
    <router-view></router-view>
    <sign-in-modal></sign-in-modal>
  </component>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';

// Import Layouts
import DefaultLayout from '@/components/layouts/DefaultLayout.vue';
import WelcomeLayout from '@/components/layouts/WelcomeLayout.vue';

// Import other components
import SignInModal from '@/components/ui/SignInModal.vue';

// Register components
Vue.component('DefaultLayout', DefaultLayout);
Vue.component('WelcomeLayout', WelcomeLayout);
Vue.component('SignInModal', SignInModal);

@Component({
  components: {
    SignInModal
  }
})
export default class App extends Vue {
  get getLayout (): string {
    return (this.$route.meta.layout || 'Default') + 'Layout';
  }
}
</script>

<style lang="scss">
@import './assets/scss/app.scss';
</style>
