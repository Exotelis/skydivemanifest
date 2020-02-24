<template>
  <div id="logo-license-status" class="clearfix">
    <div class="status-cirle float-right d-none d-xl-block" :class="status"
         :title="$t('app.license.text', { status: getLicenseStatus })"></div>
    <img src="@/assets/images/logo.png" class="logo float-left mr-0 mr-xl-4" alt="Skydivemanifest Logo">
    <p class="title d-none d-xl-block mb-1 font-weight-bold">{{ $t('app.title') }}</p>
    <p class="subtitle d-none d-xl-block mb-0">{{ $t('app.version', { version: version }) }}</p>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { TranslateResult } from 'vue-i18n';
import { version } from '@/../package.json';

@Component({})
export default class LogoLicenseStatus extends Vue {
  status: string = ''; // TODO - Get license status from when it exists
  version: string = version;

  get getLicenseStatus (): TranslateResult {
    if (this.status === 'active') {
      return this.$t('app.license.active');
    }

    if (this.status === 'inactive') {
      return this.$t('app.license.inactive');
    }

    return this.$t('app.license.undefined');
  }
}
</script>
