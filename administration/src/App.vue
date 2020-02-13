<template>
  <component :is="getLayout">
    <router-view></router-view>
  </component>
</template>

<script>
import Vue from 'vue';

// Import Layouts
import DefaultLayout from '@/components/layouts/DefaultLayout';
import WelcomeLayout from '@/components/layouts/WelcomeLayout';

// Register components
Vue.component('DefaultLayout', DefaultLayout);
Vue.component('WelcomeLayout', WelcomeLayout);

export default {
  computed: {
    getLayout: function () {
      return (this.$route.meta.layout || 'Default') + 'Layout';
    }
  }
};
</script>

<style lang="scss">
@import './assets/scss/app.scss';
</style>
