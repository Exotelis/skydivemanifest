<template>
  <main id="welcome">

    <div class="welcome-container">
      <div class="welcome-left">
        <div class="welcome-textbox">
          <div class="welcome-title">{{ getTimeBasedTitle }}</div>
          <div class="welcome-subtitle">{{ $t('pages.login.subtitle') }}</div>
          <div class="welcome-author">
            {{ $t('pages.login.imageAuthor', { author: 'wesleyjharrison', platform: 'pixabay'}) }}
          </div>
        </div>
      </div>
      <div class="welcome-right">
        <slot><router-view></router-view></slot>
      </div>
    </div>
  </main>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { TranslateResult } from 'vue-i18n';

@Component({})
export default class WelcomeLayout extends Vue {
  time: number = new Date().getHours();

  get getTimeBasedTitle (): TranslateResult {
    if (this.time >= 5 && this.time < 12) {
      return this.$t('pages.login.title.morning');
    }

    if (this.time >= 12 && this.time < 15) {
      return this.$t('pages.login.title.noon');
    }

    if (this.time >= 15 && this.time < 18) {
      return this.$t('pages.login.title.afternoon');
    }

    if (this.time >= 18 && this.time <= 23) {
      return this.$t('pages.login.title.evening');
    }

    return this.$t('pages.login.title.other');
  }
}
</script>
