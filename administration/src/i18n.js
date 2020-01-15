import Vue from 'vue';
import VueI18n from 'vue-i18n';
import axios from 'axios';
import en from '@/locales/en';

Vue.use(VueI18n);

export const defaultLanguage = localStorage.locale || process.env.VUE_APP_I18N_LOCALE || 'en';
const defaultFallbackLanguage = 'en';

export const i18n = new VueI18n({
  locale: defaultFallbackLanguage,
  fallbackLocale: defaultFallbackLanguage,
  messages: {
    [defaultFallbackLanguage]: en.messages
  },
  dateTimeFormats: {
    [defaultFallbackLanguage]: en.dateTimeFormats
  },
  availableLocales: [defaultFallbackLanguage] // our fallback language that is preloaded
});

function setI18nLanguage (lang) {
  i18n.locale = lang;
  axios.defaults.headers.common['Accept-Language'] = lang;
  document.querySelector('html').setAttribute('lang', lang);
  return lang;
}

export function loadLanguageAsync (lang) {
  const langRegex = /^[A-Za-z]{2,3}([_\-.][A-Za-z]{2,4}){0,2}$/;

  // If lang is not a valid language identifier
  if (!lang.match(langRegex)) {
    throw new Error('Invalid language identifier');
  }

  // If the same language
  if (i18n.locale === lang) {
    return Promise.resolve(setI18nLanguage(lang));
  }

  // If the language was already loaded
  if (i18n.availableLocales.includes(lang)) {
    return Promise.resolve(setI18nLanguage(lang));
  }

  // If the language hasn't been loaded yet
  return import(/* webpackChunkName: "lang-[request]" */ `@/locales/${lang}.json`).then(
    messages => {
      i18n.setLocaleMessage(lang, messages.default.messages);
      i18n.setDateTimeFormat(lang, messages.default.dateTimeFormats);
      return setI18nLanguage(lang);
    },
    () => {
      throw new Error('Language "' + lang + '" could not be loaded, use fallback instead');
    }
  );
}
