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

      <div v-if="error" class="alert alert-danger" role="alert">
        {{ error }}
      </div>

      <form @submit.prevent="login" novalidate v-validate>
        <form-group label-for="username"
                    :invalid-feedback="errors.username"
                    :label="$t('login.username.label')">
          <input-text autofocus
                      id="username"
                      required
                      v-model.trim="form.username"
                      :placeholder="$t('login.username.placeholder')"></input-text>
        </form-group>
        <form-group label-for="password"
                    :invalid-feedback="errors.password"
                    :label="$t('login.password.label')">
          <input-password id="password"
                          required
                          v-model.trim="form.password"
                          :is-toggleable="true"
                          :placeholder="$t('login.password.placeholder')"></input-password>
        </form-group>
        <div class="clearfix">
          <button-wrapper icon="mdi-login"
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
import { Component, Mixins, Watch } from 'vue-property-decorator';
import { CredentialsModel } from '@/models/CredentialsModel';
import { TranslateResult } from 'vue-i18n';
import AuthService from '@/services/AuthService';
import ButtonWrapper from '@/components/form/ButtonWrapper.vue';
import FormGroup from '@/components/form/FormGroup.vue';
import FormValidationMixin from '@/mixins/FormValidationMixin';
import InputPassword from '@/components/form/InputPassword.vue';
import InputText from '@/components/form/InputText.vue';

interface FormElements {
  username: string;
  password: string;
}

@Component({
  components: {
    ButtonWrapper,
    FormGroup,
    InputPassword,
    InputText
  }
})
export default class LoginPage extends Mixins(FormValidationMixin) {
  disabledSubmit: boolean = true;
  error: string|null = null;
  form: FormElements = {
    username: '',
    password: ''
  };
  loading: boolean = false;
  time: number = new Date().getHours();

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

  async login (): Promise<any> {
    let credentials: CredentialsModel = { username: this.form.username, password: this.form.password };
    this.loading = true;

    try {
      await AuthService.login(credentials);
      await this.$router.push('/');
    } catch (e) {
      this.validateResponse(e.response);
      this.error = e.response.data.message;
    }
    this.loading = false;
  }

  @Watch('form', { deep: true })
  onFormChange (form: FormElements): void {
    this.disabledSubmit = !(form.username.length > 0 && form.password.length > 0);
  }
}
</script>
