<template>
  <div class="dropdown">
    <button class="btn btn-link dropdown-toggle default-font-size" type="button" id="languageSelector" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
      {{ $t('general.translations') }}
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageSelector">
      <a v-for="(language, id) in languages" :key="id" class="dropdown-item" @click.prevent="changeLanguage(id)"
         href="">{{ language }}</a>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import locales from '@/locales/locales.json';
import { Component } from 'vue-property-decorator';
import { loadLanguageAsync } from '@/i18n';

@Component({})
export default class LanguageSelector extends Vue {
  languages: object = locales;

  changeLanguage (language: string): void {
    // Update page title
    Promise.all([loadLanguageAsync(language)])
      .then(() => {
        localStorage.setItem('locale', language); // Update local storage on success
      })
      .finally(() => {
        const title = process.env.VUE_APP_TITLE || 'Skydivemanifest Administration';
        document.title = typeof this.$route.meta !== 'undefined' && this.$route.meta.title
          ? this.$t(this.$route.meta.title) + ' | ' + title : title;
      });
  }
}
</script>
