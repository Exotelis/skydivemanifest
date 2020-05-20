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
                    v-model="form.username"
                    :error-text="$t('error.form.required.text')"
                    :label="$t('login.username.label')"
                    :placeholder="$t('login.username.placeholder')"
                    :required="true"></input-text>
        <input-password id="password"
                        v-model="form.password"
                        :error-text="$t('error.form.required.text')"
                        :is-toggleable="true"
                        :label="$t('login.password.label')"
                        :placeholder="$t('login.password.placeholder')"
                        :required="true"></input-password>
        <div class="clearfix">
          <button-wrapper class="test"
                          icon="mdi-login"
                          id="signin"
                          type="submit"
                          :disabled="disabledSubmit"
                          :loading="loading"
                          :right-aligned="true">{{ $t('login.signIn') }}</button-wrapper>
        </div>
      </form>
    </div>
  </div>
</template>

<script lang="ts">
import Vue from 'vue';
import { Component, Watch } from 'vue-property-decorator';
import { TranslateResult } from 'vue-i18n';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import InputPassword from '@/components/form/InputPassword.vue';
import InputText from '@/components/form/InputText.vue';

interface FormElements {
  username: string;
  password: string;
}

@Component({
  components: {
    ButtonWrapper,
    InputPassword,
    InputText
  }
})
export default class LoginPage extends Vue {
  time: number = new Date().getHours();
  form: FormElements = {
    username: '',
    password: ''
  };
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

  login (element: Event): void {
    const form = element.target as HTMLInputElement;
    form.classList.add('was-validated');

    this.loading = true;
    setTimeout(() => {
      this.loading = false;
    }, 3000);
    // Todo Login logic
  }

  @Watch('form', { deep: true })
  onFormChange (form: FormElements): void {
    this.disabledSubmit = !(form.username.trim().length > 0 && this.form.password.trim().length > 0);
  }
}
</script>
