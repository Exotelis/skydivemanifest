<template>
  <div class="welcome-container">
    <div class="welcome-left">
      <div class="welcome-textbox">
        <div class="welcome-title">{{ getTimeBasedTitle }}</div>
        <div class="welcome-subtitle">{{ $t('login.subtitle') }}</div>
        <div class="welcome-author">{{ $t('login.imageAuthor', { author: 'wesleyjharrison', platform: 'pixabay'}) }}</div>
      </div>
    </div>
    <div class="welcome-right">
      <h3>{{ $t('login.formHeader') }}</h3>

      <form @submit.prevent="login" novalidate>
        <input-text autofocus
                    id="username"
                    v-model="username"
                    :error-text="$t('error.required')"
                    :label="$t('login.username.label')"
                    :placeholder="$t('login.username.placeholder')"
                    :required="true"></input-text>
        <input-password id="password"
                        v-model="password"
                        :error-text="$t('error.required')"
                        :is-toggleable="true"
                        :label="$t('login.password.label')"
                        :placeholder="$t('login.password.placeholder')"
                        :required="true"></input-password>
        <div class="clearfix">
          <button-submit icon="mdi-login"
                         id="signin"
                         :disabled="disabledSubmit"
                         :loading="loading"
                         :right-aligned="true">{{ $t('login.signIn') }}</button-submit>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Watch } from 'vue-property-decorator';
import { TranslateResult } from 'vue-i18n';
import ButtonSubmit from '@/components/form/ButtonSubmit.vue';
import InputPassword from '@/components/form/InputPassword.vue';
import InputText from '@/components/form/InputText.vue';

@Component({
  components: {
    ButtonSubmit,
    InputPassword,
    InputText
  }
})
export default class LoginPage extends Vue {
  time: number = new Date().getHours();
  username: string = '';
  password: string = '';
  loading: boolean = false;
  disabledSubmit: boolean = true;

  get getTimeBasedTitle (): TranslateResult {
    if (this.time >= 5 && this.time < 12) {
      return this.$t('login.title.morning');
    }

    if (this.time >= 12 && this.time < 15) {
      return this.$t('login.title.noon');
    }

    if (this.time >= 15 && this.time < 18) {
      return this.$t('login.title.afternoon');
    }

    if (this.time >= 18 && this.time <= 23) {
      return this.$t('login.title.evening');
    }

    return this.$t('login.title.other');
  }

  get usernamePassword (): string {
    return this.username + this.password;
  }

  login (element: Event): void {
    const form = element.target as HTMLInputElement;
    form.classList.add('was-validated');

    this.loading = true;
    setTimeout(() => {
      this.loading = false;
    }, 3000);
    // Todo Login logic
  }

  @Watch('usernamePassword')
  onUsernamePasswordChange (): void {
    this.username.trim() !== '' && this.password.trim() !== '' ? this.disabledSubmit = false
      : this.disabledSubmit = true;
  }
}
</script>
