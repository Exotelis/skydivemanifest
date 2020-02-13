<template>
  <div class="dropdown">
    <button class="btn btn-link dropdown-toggle" type="button" id="languageSelector" data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
      {{ $t('general.translations') }}
    </button>
    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="languageSelector">
      <a v-for="(language, id) in languages" :key="id" class="dropdown-item" @click.prevent="changeLanguage(id)"
         href="">{{ language }}</a>
    </div>
  </div>
</template>

<script>
import { loadLanguageAsync } from '@/i18n';
import locales from '@/locales/locales';

export default {
  name: 'LanguageSelector',
  data: function () {
    return {
      languages: locales
    };
  },
  methods: {
    changeLanguage: function (language) {
      // Update page title
      Promise.all([loadLanguageAsync(language)])
        .then(() => {
          localStorage.setItem('locale', language); // Update local storage on success
        })
        .finally(() => {
          document.title = typeof this.$route.meta !== 'undefined' && this.$route.meta.title
            ? this.$t(this.$route.meta.title) + ' | ' + process.env.VUE_APP_TITLE
            : process.env.VUE_APP_TITLE;
        });
    }
  }
};
</script>
