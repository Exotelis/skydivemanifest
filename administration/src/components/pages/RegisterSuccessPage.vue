<template>
  <div class="my-4">
    <h3>{{ $t('page.registerSuccess.header') }}</h3>

    <p v-html="$t('page.registerSuccess.confirmation', { email: email })"></p>
    <p><router-link to="/login">{{ $t('general.signInNow') }}</router-link></p>
    <p><small><em>{{ $t('page.registerSuccess.note') }} {{ $t('page.registerSuccess.contactUs') }}</em></small></p>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component } from 'vue-property-decorator';
import { htmlEntities } from '@/helpers';

@Component({})
export default class RegisterSuccessPage extends Vue {
  email: string = '';

  mounted () {
    const email: string|null = localStorage.getItem('tmpEmail');

    if (email === null) {
      this.$router.push('/login');
    } else {
      this.email = htmlEntities(email);
      localStorage.removeItem('tmpEmail');
    }
  }
}
</script>
